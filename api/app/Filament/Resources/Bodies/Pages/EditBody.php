<?php

namespace App\Filament\Resources\Bodies\Pages;

use App\Filament\Resources\Bodies\BodyResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBody extends EditRecord
{
    protected static string $resource = BodyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
