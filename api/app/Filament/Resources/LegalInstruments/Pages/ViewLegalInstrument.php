<?php

namespace App\Filament\Resources\LegalInstruments\Pages;

use App\Filament\Resources\LegalInstruments\LegalInstrumentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLegalInstrument extends ViewRecord
{
    protected static string $resource = LegalInstrumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
