<?php

use App\Filament\Resources\Bodies\BodyResource;
use App\Filament\Resources\Edges\EdgeResource;
use App\Filament\Resources\LegalInstruments\LegalInstrumentResource;
use App\Filament\Resources\People\PersonResource;
use App\Filament\Resources\Positions\PositionResource;
use App\Filament\Resources\Tenures\TenureResource;
use App\Models\User;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

it('renders every resource index and create page', function (string $resource) {
    $this->get($resource::getUrl('index'))->assertOk();
    $this->get($resource::getUrl('create'))->assertOk();
})->with([
    BodyResource::class,
    PositionResource::class,
    PersonResource::class,
    TenureResource::class,
    EdgeResource::class,
    LegalInstrumentResource::class,
]);
