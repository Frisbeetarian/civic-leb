<?php

namespace App\Filament\Resources\Tenures\Pages;

use App\Filament\Resources\Tenures\TenureResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTenure extends EditRecord
{
    protected static string $resource = TenureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
