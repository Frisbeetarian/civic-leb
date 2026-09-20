<?php

use App\Enums\ReviewState;
use App\Models\Body;
use App\Models\Edge;
use App\Models\Person;
use App\Models\Position;
use App\Models\Tenure;

function publishedBody(array $attrs = []): Body
{
    return Body::create(array_merge([
        'slug' => 'lb-test-'.fake()->unique()->slug(2),
        'type' => 'department',
        'subtype' => 'ministry',
        'sector' => 'executive',
        'name_ar' => 'وزارة',
        'name_en' => 'Ministry',
        'review_state' => ReviewState::Published,
        'published_at' => now(),
    ], $attrs));
}

it('exposes only published bodies, positions and edges', function () {
    $electorate = publishedBody(['slug' => 'lb-electorate', 'type' => 'constituency', 'sector' => 'executive', 'name_en' => 'People of Lebanon']);
    $ministry = publishedBody(['slug' => 'lb-ministry-of-finance', 'name_en' => 'Ministry of Finance']);
    $draft = publishedBody(['slug' => 'lb-draft', 'review_state' => ReviewState::Draft]);

    $minister = Position::create([
        'slug' => 'lb-minister-of-finance', 'body_id' => $ministry->id, 'kind' => 'minister', 'title_ar' => 'وزير المالية', 'title_en' => 'Minister of Finance',
        'confession' => 'shia', 'confession_basis' => 'custom', 'confession_source_url' => 'https://example.org',
        'review_state' => ReviewState::Published, 'published_at' => now(),
    ]);
    $person = Person::create(['slug' => 'lb-yassine-jaber', 'name_ar' => 'ياسين جابر', 'name_en' => 'Yassine Jaber', 'review_state' => ReviewState::Published]);
    Tenure::create(['position_id' => $minister->id, 'person_id' => $person->id, 'status' => 'substantive', 'effective_date' => '2025-02-08', 'review_state' => ReviewState::Published]);

    Edge::create(['type' => 'dept_head', 'from_type' => 'body', 'from_id' => $ministry->id, 'to_type' => 'position', 'to_id' => $minister->id, 'review_state' => ReviewState::Published]);
    Edge::create(['type' => 'tutelage', 'from_type' => 'body', 'from_id' => $ministry->id, 'to_type' => 'body', 'to_id' => $draft->id, 'review_state' => ReviewState::Published]);

    $response = $this->getJson('/api/lb/graph')->assertOk();
    $nodes = $response->json('nodes');

    expect($nodes)->toHaveKeys(['lb-electorate', 'lb-ministry-of-finance', 'lb-minister-of-finance'])
        ->and($nodes)->not->toHaveKey('lb-draft')
        ->and($response->json('edges'))->toHaveCount(1)
        ->and($nodes['lb-ministry-of-finance']['head'])->toBe('lb-minister-of-finance')
        ->and($nodes['lb-ministry-of-finance']['people'][0]['name']['en'])->toBe('Yassine Jaber')
        ->and($nodes['lb-minister-of-finance']['confession'])->toBe('shia')
        ->and($response->json('layout.id'))->toBe('lb-sectors');

    $this->getJson('/api/lb/nodes/lb-ministry-of-finance')->assertOk()->assertJsonPath('node.id', 'lb-ministry-of-finance')->assertJsonCount(1, 'edges');
    $this->getJson('/api/lb/nodes/lb-draft')->assertNotFound();
});

it('records a vacancy when a tenure has no person', function () {
    $body = publishedBody();
    $position = Position::create(['slug' => 'lb-dg-x', 'body_id' => $body->id, 'kind' => 'head', 'title_ar' => 'مدير عام', 'title_en' => 'Director General', 'review_state' => ReviewState::Published]);
    $tenure = Tenure::create(['position_id' => $position->id, 'status' => 'substantive', 'vacancy_reason' => 'expired', 'decision_date' => '2026-01-01', 'review_state' => ReviewState::Published]);

    expect($tenure->status->value)->toBe('vacant')->and($tenure->start_date->toDateString())->toBe('2026-01-01');
    expect($this->getJson('/api/lb/graph')->json('counts.vacantSeats'))->toBe(1);
});
