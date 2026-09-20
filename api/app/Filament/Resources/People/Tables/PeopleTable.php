<?php

namespace App\Filament\Resources\People\Tables;

use App\Filament\Support\Review;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PeopleTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name_en')
            ->columns([
                TextColumn::make('name_en')->label('Name')->searchable(['name_en', 'name_ar', 'slug'])->sortable()->description(fn ($record) => $record->name_ar),
                TextColumn::make('party')->toggleable(),
                TextColumn::make('tenures_count')->counts('tenures')->label('Tenures'),
                TextColumn::make('wikidata_qid')->label('QID')->toggleable(),
                Review::column(),
            ])
            ->filters([Review::filter()])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                ActionGroup::make(Review::recordActions()),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ...Review::bulkActions(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
