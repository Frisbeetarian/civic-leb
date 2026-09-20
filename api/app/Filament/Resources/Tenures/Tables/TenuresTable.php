<?php

namespace App\Filament\Resources\Tenures\Tables;

use App\Enums\TenureStatus;
use App\Filament\Support\Review;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TenuresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('start_date', 'desc')
            ->columns([
                TextColumn::make('person.name_en')->label('Person')->placeholder('— vacancy —')->searchable()->sortable(),
                TextColumn::make('position.title_en')->label('Position')->searchable()->sortable()->description(fn ($record) => $record->position?->body?->name_en),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('start_date')->date()->sortable(),
                TextColumn::make('end_date')->date()->sortable()->placeholder('current'),
                TextColumn::make('legalInstrument.title_en')->label('Instrument')->toggleable()->limit(40),
                Review::column(),
            ])
            ->filters([
                SelectFilter::make('status')->options(TenureStatus::class),
                TernaryFilter::make('current')->label('Current')->queries(
                    true: fn ($q) => $q->whereNull('end_date'),
                    false: fn ($q) => $q->whereNotNull('end_date'),
                ),
                Review::filter(),
            ])
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
