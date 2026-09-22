<?php

namespace Database\Seeders;

use App\Enums\ReviewState;
use App\Models\Body;
use App\Models\Edge;
use App\Models\LegalInstrument;
use App\Models\Person;
use App\Models\Position;
use App\Models\Tenure;
use App\Services\Graph\GraphExporter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Loads database/seeders/data/core.php idempotently (upsert by slug / natural key).
 * Rows are created as drafts; set CIVICLEB_SEED_PUBLISH=1 to publish everything seeded
 * (local development only, so the graph renders without a manual review pass).
 */
class CoreGraphSeeder extends Seeder
{
    public function run(): void
    {
        $this->apply($this->load(), filter_var(env('CIVICLEB_SEED_PUBLISH', false), FILTER_VALIDATE_BOOL));
    }

    /** The merged seed dataset: core.php first, then every other data file (committees, seats, later blocs). */
    public function load(): array
    {
        $data = require database_path('seeders/data/core.php');
        foreach (glob(database_path('seeders/data/*.php')) as $file) {
            if (basename($file) === 'core.php') {
                continue;
            }
            $extra = require $file;
            foreach ($extra as $key => $rows) {
                $data[$key] = array_merge($data[$key] ?? [], $rows);
            }
        }

        return $data;
    }

    /** Upserts one dataset (the shape of load()) as drafts, or as published rows when $publish is set. */
    public function apply(array $data, bool $publish): void
    {
        $review = $publish
            ? ['review_state' => ReviewState::Published, 'reviewed_at' => now(), 'published_at' => now()]
            : ['review_state' => ReviewState::Draft];

        DB::transaction(function () use ($data, $review) {
            // instruments (two passes so annulled_by / superseded_by can reference each other)
            $instruments = [];
            $instrumentLinks = [];
            foreach ($data['instruments'] as $row) {
                $key = $row['key'];
                unset($row['key']);
                $links = ['annulled_by' => $row['annulled_by'] ?? null, 'superseded_by' => $row['superseded_by'] ?? null];
                unset($row['annulled_by'], $row['superseded_by']);
                $instruments[$key] = LegalInstrument::updateOrCreate(
                    ['kind' => $row['kind'], 'number' => $row['number'] ?? null, 'date' => $row['date'] ?? null, 'title_en' => $row['title_en'] ?? null],
                    $row,
                );
                $instrumentLinks[$key] = $links;
            }
            foreach ($instruments as $key => $instrument) {
                $links = $instrumentLinks[$key];
                if (($links['annulled_by'] ?? null) && isset($instruments[$links['annulled_by']])) {
                    $instrument->annulled_by_id = $instruments[$links['annulled_by']]->id;
                }
                if (($links['superseded_by'] ?? null) && isset($instruments[$links['superseded_by']])) {
                    $instrument->superseded_by_id = $instruments[$links['superseded_by']]->id;
                }
                $instrument->save();
            }

            // bodies (two passes for parent links)
            $bodies = [];
            $bodyParents = [];
            foreach ($data['bodies'] as $row) {
                $sources = $row['sources'] ?? [];
                $parent = $row['parent'] ?? null;
                $instrument = $row['instrument'] ?? null;
                unset($row['sources'], $row['parent'], $row['instrument']);
                $row['legal_instrument_id'] = $instrument ? $instruments[$instrument]->id : null;
                $row['aliases'] ??= ['ar' => [], 'en' => [], 'fr' => []];
                $bodies[$row['slug']] = Body::updateOrCreate(['slug' => $row['slug']], $row + $review);
                $bodyParents[$row['slug']] = $parent;
                $this->syncSources($bodies[$row['slug']], $sources);
            }
            foreach ($bodies as $slug => $body) {
                if ($bodyParents[$slug]) {
                    $body->parent_id = $bodies[$bodyParents[$slug]]->id;
                    $body->level = ($bodies[$bodyParents[$slug]]->level ?? 0) + 1;
                    $body->save();
                }
            }

            // positions
            $positions = [];
            foreach ($data['positions'] as $row) {
                $sources = $row['sources'] ?? [];
                $body = $row['body'];
                $authority = $row['appointing_authority'] ?? null;
                $instrument = $row['instrument'] ?? null;
                unset($row['sources'], $row['body'], $row['appointing_authority'], $row['instrument']);
                $row['body_id'] = $bodies[$body]->id;
                $row['appointing_authority_id'] = $authority ? $bodies[$authority]->id : null;
                $row['legal_instrument_id'] = $instrument ? $instruments[$instrument]->id : null;
                $positions[$row['slug']] = Position::updateOrCreate(['slug' => $row['slug']], $row + $review);
                $this->syncSources($positions[$row['slug']], $sources);
            }

            // persons
            $persons = [];
            foreach ($data['persons'] as $row) {
                $persons[$row['slug']] = Person::updateOrCreate(['slug' => $row['slug']], $row + $review);
            }

            // tenures (natural key: position + person + start_date)
            $previousByPosition = [];
            foreach ($data['tenures'] as $row) {
                $sources = $row['sources'] ?? [];
                $position = $positions[$row['position']];
                // a seat holder may be defined in another data file (core.php, committees.php); a slug missing
                // from all of them must fail here, not silently seed a person-less tenure
                if ($row['person'] && ! isset($persons[$row['person']])) {
                    throw new \RuntimeException("Unknown person slug in tenures for {$row['position']}: {$row['person']}");
                }
                $person = $row['person'] ? $persons[$row['person']] : null;
                $instrument = $row['instrument'] ?? null;
                unset($row['sources'], $row['position'], $row['person'], $row['instrument']);
                $row['position_id'] = $position->id;
                $row['person_id'] = $person?->id;
                $row['legal_instrument_id'] = $instrument ? $instruments[$instrument]->id : null;
                $row['start_date'] ??= $row['effective_date'] ?? $row['instrument_date'] ?? $row['decision_date'];
                if (isset($previousByPosition[$position->id])) {
                    $row['predecessor_tenure_id'] = $previousByPosition[$position->id]->id;
                }
                $tenure = Tenure::updateOrCreate(
                    ['position_id' => $position->id, 'person_id' => $person?->id, 'start_date' => $row['start_date']],
                    $row + $review,
                );
                $this->syncSources($tenure, $sources);
                $previousByPosition[$position->id] = $tenure;
            }

            // every graph-node position gets a structural dept_head edge from its body (CivLab convention)
            foreach ($positions as $position) {
                if (! $position->is_graph_node) {
                    continue;
                }
                Edge::firstOrCreate(
                    ['type' => 'dept_head', 'from_type' => 'body', 'from_id' => $position->body_id, 'to_type' => 'position', 'to_id' => $position->id],
                    ['seats_appointed' => 0] + $review,
                );
            }

            // edges
            $resolve = function (string $slug) use ($bodies, $positions): array {
                if (isset($bodies[$slug])) {
                    return ['body', $bodies[$slug]->id];
                }
                if (isset($positions[$slug])) {
                    return ['position', $positions[$slug]->id];
                }
                throw new \RuntimeException("Unknown node slug in edges: {$slug}");
            };
            foreach ($data['edges'] as $row) {
                [$fromType, $fromId] = $resolve($row['from']);
                [$toType, $toId] = $resolve($row['to']);
                $instrument = $row['instrument'] ?? null;
                Edge::updateOrCreate(
                    ['type' => $row['type'], 'from_type' => $fromType, 'from_id' => $fromId, 'to_type' => $toType, 'to_id' => $toId],
                    [
                        'seats_appointed' => $row['seats'] ?? 0,
                        'metadata' => $row['metadata'] ?? null,
                        'legal_instrument_id' => $instrument ? $instruments[$instrument]->id : null,
                    ] + $review,
                );
            }
        });

        app(GraphExporter::class)->invalidate();

        $this->command?->info(sprintf(
            'Seeded %d instruments, %d bodies, %d positions, %d persons, %d tenures, %d edges (%s).',
            count($data['instruments']), count($data['bodies']), count($data['positions']), count($data['persons']), count($data['tenures']), count($data['edges']),
            $publish ? 'published' : 'draft',
        ));
    }

    /** @param array<int, array{0:string,1:string,2?:string}> $sources */
    private function syncSources($model, array $sources): void
    {
        foreach ($sources as [$url, $kind, $title]) {
            $model->sources()->updateOrCreate(['url' => $url], ['kind' => $kind, 'title' => $title ?? null, 'fetched_at' => now()]);
        }
    }
}
