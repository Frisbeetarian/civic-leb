<?php

namespace App\Services\Graph;

use App\Enums\PositionKind;
use App\Enums\ReviewState;
use App\Models\Body;
use App\Models\Edge;
use App\Models\LegalInstrument;
use App\Models\Position;
use App\Models\Tenure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

/**
 * Builds the published graph snapshot consumed by the frontend (decisions.md: one JSON
 * document with nodes, edges, people per node, counts and the layout descriptor).
 * Only published rows are included; an edge is included only when both ends are published.
 */
class GraphExporter
{
    public const GOV = 'lb';

    public const LAYOUT = 'lb-sectors';

    private const CACHE_KEY = 'graph:lb:snapshot';

    private const VERSION_KEY = 'graph:lb:version';

    public function snapshot(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => $this->build());
    }

    public function version(): string
    {
        return (string) Cache::rememberForever(self::VERSION_KEY, fn () => now()->format('YmdHis'));
    }

    public function invalidate(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forever(self::VERSION_KEY, now()->format('YmdHis'));
    }

    public function build(): array
    {
        $bodies = Body::published()->with(['legalInstrument', 'parent'])->get()->keyBy('id');
        $positions = Position::published()->with(['body', 'legalInstrument', 'appointingAuthority'])->get()->keyBy('id');
        $tenures = Tenure::published()->whereNull('end_date')->with(['person', 'legalInstrument'])->get()->groupBy('position_id');
        $edges = Edge::published()->with('legalInstrument')->get();

        $slugOf = function (string $type, int $id) use ($bodies, $positions): ?string {
            return match ($type) {
                'body' => $bodies->get($id)?->slug,
                'position' => $positions->get($id)?->slug,
                default => null,
            };
        };

        $nodes = [];
        $edgesOut = [];
        $edgeIndex = []; // slug => [edgeId,...]
        $neighbours = []; // slug => [slug,...]

        foreach ($edges as $edge) {
            $from = $slugOf($edge->from_type, $edge->from_id);
            $to = $slugOf($edge->to_type, $edge->to_id);
            if (! $from || ! $to) {
                continue; // one end unpublished
            }
            $eid = (string) $edge->id;
            $edgesOut[$eid] = [
                'id' => $eid,
                'type' => $edge->type->value,
                'fromId' => $from,
                'toId' => $to,
                'seatsAppointed' => $edge->seats_appointed,
                'metadata' => $edge->metadata ?? new \stdClass,
                'legalSource' => $this->instrument($edge->legalInstrument),
            ];
            $edgeIndex[$from][] = $eid;
            $edgeIndex[$to][] = $eid;
            $neighbours[$from][] = $to;
            $neighbours[$to][] = $from;
        }

        $childrenOf = $bodies->groupBy('parent_id');
        $positionsOf = $positions->groupBy('body_id');

        foreach ($bodies as $body) {
            // the head shown on the body page: the most senior graph-node position, in this order
            $order = [PositionKind::President, PositionKind::PrimeMinister, PositionKind::Speaker, PositionKind::Head, PositionKind::Chair, PositionKind::Minister];
            $head = collect($order)->map(fn ($k) => $positionsOf->get($body->id)?->first(fn (Position $p) => $p->is_graph_node && $p->kind === $k))->first(fn ($p) => $p !== null);
            $people = [];
            foreach ($positionsOf->get($body->id, collect()) as $position) {
                foreach ($tenures->get($position->id, collect()) as $tenure) {
                    $people[] = $this->person($tenure, $position);
                }
            }
            $nodes[$body->slug] = [
                'id' => $body->slug,
                'type' => $body->type->value,
                'subtype' => $body->subtype?->value,
                'legalForm' => $body->legal_form?->value,
                'sector' => $body->sector->value,
                'name' => ['ar' => $body->name_ar, 'en' => $body->name_en, 'fr' => $body->name_fr],
                'description' => ['ar' => $body->description_ar, 'en' => $body->description_en],
                'aliases' => $body->aliases ?? ['ar' => [], 'en' => [], 'fr' => []],
                'officialUrl' => $body->official_url,
                'legalSource' => $this->instrument($body->legalInstrument),
                'parent' => $body->parent_id ? $bodies->get($body->parent_id)?->slug : null,
                'level' => $body->level,
                'children' => $childrenOf->get($body->id, collect())->pluck('slug')->values()->all(),
                'head' => $head?->slug,
                'positions' => $positionsOf->get($body->id, collect())->pluck('slug')->values()->all(),
                'seatsCount' => $body->seats_count,
                'functions' => $body->functions,
                'stateFunded' => $body->state_funded,
                'ownership' => $body->ownership,
                'status' => $body->status->value,
                'statusNote' => $body->status_note,
                'layoutHints' => $body->layout_hints,
                'people' => $people,
                'edges' => array_values(array_unique($edgeIndex[$body->slug] ?? [])),
                'connectedNodes' => array_values(array_unique($neighbours[$body->slug] ?? [])),
            ];
        }

        foreach ($positions as $position) {
            if (! $position->is_graph_node) {
                continue;
            }
            $people = $tenures->get($position->id, collect())->map(fn (Tenure $t) => $this->person($t, $position))->values()->all();
            $body = $bodies->get($position->body_id);
            $nodes[$position->slug] = [
                'id' => $position->slug,
                'type' => $position->kind === PositionKind::Seat ? 'seat' : 'dept_head',
                'kind' => $position->kind->value,
                'sector' => $body?->sector->value,
                'name' => ['ar' => $position->title_ar, 'en' => $position->title_en, 'fr' => $position->title_fr],
                'description' => ['ar' => $position->description_ar, 'en' => $position->description_en],
                'aliases' => ['ar' => [], 'en' => [], 'fr' => []],
                'headOf' => $body?->slug,
                'legalSource' => $this->instrument($position->legalInstrument),
                'grade' => $position->grade?->value,
                'appointingAuthority' => $position->appointingAuthority?->slug,
                'confession' => $position->confession?->value,
                'confessionBasis' => $position->confession_basis?->value,
                'confessionSourceUrl' => $position->confession_source_url,
                'seat' => $position->kind === PositionKind::Seat ? [
                    'majorDistrict' => $position->seat_major_district,
                    'minorDistrict' => $position->seat_minor_district,
                    'ordinal' => $position->seat_ordinal,
                ] : null,
                'termYears' => $position->term_years,
                'status' => $position->status->value,
                'people' => $people,
                'edges' => array_values(array_unique($edgeIndex[$position->slug] ?? [])),
                'connectedNodes' => array_values(array_unique(array_merge($neighbours[$position->slug] ?? [], $body ? [$body->slug] : []))),
            ];
        }

        $counts = [
            'byType' => collect($nodes)->countBy('type')->all(),
            'bySector' => collect($nodes)->countBy(fn ($n) => $n['sector'] ?? 'none')->all(),
            'vacantSeats' => collect($nodes)->filter(fn ($n) => in_array($n['type'], ['dept_head', 'seat']) && collect($n['people'])->every(fn ($p) => $p['status'] === 'vacant'))->count(),
            'actingOfficials' => collect($nodes)->flatMap(fn ($n) => $n['people'])->whereIn('status', ['acting', 'assigned'])->count(),
        ];

        return [
            'gov' => self::GOV,
            'generatedAt' => now()->toIso8601String(),
            'layout' => $this->layout(),
            'counts' => $counts,
            'nodes' => $nodes,
            'edges' => $edgesOut,
        ];
    }

    public function layout(): array
    {
        $path = resource_path('layouts/'.self::LAYOUT.'.json');

        return File::exists($path) ? json_decode(File::get($path), true) : ['id' => self::LAYOUT];
    }

    private function person(Tenure $tenure, Position $position): array
    {
        return [
            'id' => $tenure->person?->slug,
            'name' => $tenure->person ? ['ar' => $tenure->person->name_ar, 'en' => $tenure->person->name_en, 'fr' => $tenure->person->name_fr] : null,
            'positionId' => $position->slug,
            'positionName' => ['ar' => $position->title_ar, 'en' => $position->title_en],
            'status' => $tenure->status->value,
            'vacancyReason' => $tenure->vacancy_reason?->value,
            'startedAt' => $tenure->start_date?->toDateString(),
            'party' => $tenure->party ?? $tenure->person?->party,
            'bloc' => $tenure->bloc,
            'imageUrl' => $tenure->person?->portrait_path,
            'legalSource' => $this->instrument($tenure->legalInstrument),
        ];
    }

    private function instrument(?LegalInstrument $i): ?array
    {
        if (! $i) {
            return null;
        }

        return [
            'kind' => $i->kind->value,
            'number' => $i->number,
            'date' => $i->date?->toDateString(),
            'title' => ['ar' => $i->title_ar, 'en' => $i->title_en],
            'inForce' => $i->in_force,
            'gazetteIssue' => $i->gazette_issue,
            'gazetteUrl' => $i->gazette_url,
            'url' => $i->text_url ?? $i->source_url ?? $i->gazette_url,
        ];
    }
}
