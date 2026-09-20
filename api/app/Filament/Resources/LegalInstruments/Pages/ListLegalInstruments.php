<?php

namespace App\Filament\Resources\LegalInstruments\Pages;

use App\Filament\Resources\LegalInstruments\LegalInstrumentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLegalInstruments extends ListRecords
{
    protected static string $resource = LegalInstrumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
