<?php

namespace App\Filament\Resources\Tenures\Pages;

use App\Filament\Resources\Tenures\TenureResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTenure extends ViewRecord
{
    protected static string $resource = TenureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
