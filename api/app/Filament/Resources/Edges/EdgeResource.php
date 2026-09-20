<?php

namespace App\Filament\Resources\Edges;

use App\Filament\Resources\Edges\Pages\CreateEdge;
use App\Filament\Resources\Edges\Pages\EditEdge;
use App\Filament\Resources\Edges\Pages\ListEdges;
use App\Filament\Resources\Edges\Pages\ViewEdge;
use App\Filament\Resources\Edges\Schemas\EdgeForm;
use App\Filament\Resources\Edges\Schemas\EdgeInfolist;
use App\Filament\Resources\Edges\Tables\EdgesTable;
use App\Models\Edge;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EdgeResource extends Resource
{
    protected static ?string $model = Edge::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static string|\UnitEnum|null $navigationGroup = 'Graph';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return EdgeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return EdgeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EdgesTable::configure($table);
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
            'index' => ListEdges::route('/'),
            'create' => CreateEdge::route('/create'),
            'view' => ViewEdge::route('/{record}'),
            'edit' => EditEdge::route('/{record}/edit'),
        ];
    }
}
