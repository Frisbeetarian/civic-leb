<?php

namespace App\Filament\Resources\Tenures;

use App\Filament\Resources\Tenures\Pages\CreateTenure;
use App\Filament\Resources\Tenures\Pages\EditTenure;
use App\Filament\Resources\Tenures\Pages\ListTenures;
use App\Filament\Resources\Tenures\Pages\ViewTenure;
use App\Filament\Resources\Tenures\Schemas\TenureForm;
use App\Filament\Resources\Tenures\Schemas\TenureInfolist;
use App\Filament\Resources\Tenures\Tables\TenuresTable;
use App\Models\Tenure;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TenureResource extends Resource
{
    protected static ?string $model = Tenure::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    protected static string|\UnitEnum|null $navigationGroup = 'People';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return TenureForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TenureInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TenuresTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTenures::route('/'),
            'create' => CreateTenure::route('/create'),
            'view' => ViewTenure::route('/{record}'),
            'edit' => EditTenure::route('/{record}/edit'),
        ];
    }
}
