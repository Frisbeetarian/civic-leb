<?php

namespace App\Filament\Resources\Edges\Tables;

use App\Enums\EdgeType;
use App\Filament\Support\Review;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EdgesTable
{
    public static function configure(Table $table): Table
    {
        $label = fn ($model) => $model?->name_en ?? $model?->title_en ?? '?';

        return $table
            ->defaultSort('type')
            ->columns([
                TextColumn::make('from')->label('From')->state(fn ($record) => $label($record->from)),
                TextColumn::make('type')->badge()->sortable(),
                TextColumn::make('to')->label('To')->state(fn ($record) => $label($record->to)),
                TextColumn::make('seats_appointed')->label('Seats')->toggleable(),
                TextColumn::make('legalInstrument.title_en')->label('Basis')->limit(40)->toggleable(),
                Review::column(),
            ])
            ->filters([
                SelectFilter::make('type')->options(EdgeType::class),
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
