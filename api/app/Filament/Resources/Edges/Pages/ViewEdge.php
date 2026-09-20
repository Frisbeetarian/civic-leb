<?php

namespace App\Filament\Resources\Edges\Pages;

use App\Filament\Resources\Edges\EdgeResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewEdge extends ViewRecord
{
    protected static string $resource = EdgeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
