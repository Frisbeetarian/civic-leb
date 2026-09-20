<?php

namespace App\Filament\Resources\Bodies;

use App\Filament\Resources\Bodies\Pages\CreateBody;
use App\Filament\Resources\Bodies\Pages\EditBody;
use App\Filament\Resources\Bodies\Pages\ListBodies;
use App\Filament\Resources\Bodies\Pages\ViewBody;
use App\Filament\Resources\Bodies\Schemas\BodyForm;
use App\Filament\Resources\Bodies\Schemas\BodyInfolist;
use App\Filament\Resources\Bodies\Tables\BodiesTable;
use App\Models\Body;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BodyResource extends Resource
{
    protected static ?string $model = Body::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    protected static string|\UnitEnum|null $navigationGroup = 'Graph';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name_en';

    public static function form(Schema $schema): Schema
    {
        return BodyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BodyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BodiesTable::configure($table);
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
            'index' => ListBodies::route('/'),
            'create' => CreateBody::route('/create'),
            'view' => ViewBody::route('/{record}'),
            'edit' => EditBody::route('/{record}/edit'),
        ];
    }
}
