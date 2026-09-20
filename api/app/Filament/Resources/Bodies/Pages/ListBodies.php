<?php

namespace App\Filament\Resources\Bodies\Pages;

use App\Filament\Resources\Bodies\BodyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBodies extends ListRecords
{
    protected static string $resource = BodyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
