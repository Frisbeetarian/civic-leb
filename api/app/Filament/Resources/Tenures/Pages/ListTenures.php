<?php

namespace App\Filament\Resources\Tenures\Pages;

use App\Filament\Resources\Tenures\TenureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTenures extends ListRecords
{
    protected static string $resource = TenureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
