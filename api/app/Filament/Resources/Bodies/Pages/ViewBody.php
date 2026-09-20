<?php

namespace App\Filament\Resources\Bodies\Pages;

use App\Filament\Resources\Bodies\BodyResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBody extends ViewRecord
{
    protected static string $resource = BodyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
