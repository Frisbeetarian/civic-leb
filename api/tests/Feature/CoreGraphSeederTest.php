<?php

use App\Services\Graph\GraphExporter;
use Database\Seeders\CoreGraphSeeder;

it('seeds the 128 parliamentary seats, each with a named holder except the documented vacancy', function () {
    $seeder = new CoreGraphSeeder;
    $seeder->apply($seeder->load(), publish: true);

    $nodes = app(GraphExporter::class)->build()['nodes'];
    $seats = collect($nodes)->filter(fn ($n) => $n['type'] === 'seat');
    // a seat whose people carry no name means a tenure was seeded without its person (or a real vacancy)
    $unnamed = $seats->filter(fn ($n) => collect($n['people'])->every(fn ($p) => $p['name'] === null))->keys()->all();

    expect($seats)->toHaveCount(128)
        ->and($unnamed)->toBe(['lb-seat-west-bekaa-rashaya-greek_orthodox-1']); // West Bekaa Greek Orthodox, vacant since 13 Dec 2025
});

it('refuses a tenure whose person is not in the seed data', function () {
    $data = [
        'instruments' => [],
        'bodies' => [['slug' => 'lb-test-body', 'type' => 'department', 'sector' => 'executive', 'name_ar' => 'هيئة', 'name_en' => 'Body']],
        'positions' => [['slug' => 'lb-test-head', 'body' => 'lb-test-body', 'kind' => 'head', 'title_ar' => 'رئيس', 'title_en' => 'Head']],
        'persons' => [],
        'tenures' => [['position' => 'lb-test-head', 'person' => 'lb-nobody', 'status' => 'substantive', 'effective_date' => '2026-01-01']],
        'edges' => [],
    ];

    expect(fn () => (new CoreGraphSeeder)->apply($data, publish: false))
        ->toThrow(RuntimeException::class, 'lb-nobody');
});
