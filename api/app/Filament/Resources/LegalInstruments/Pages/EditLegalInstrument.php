<?php

namespace App\Filament\Resources\LegalInstruments\Pages;

use App\Filament\Resources\LegalInstruments\LegalInstrumentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLegalInstrument extends EditRecord
{
    protected static string $resource = LegalInstrumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
