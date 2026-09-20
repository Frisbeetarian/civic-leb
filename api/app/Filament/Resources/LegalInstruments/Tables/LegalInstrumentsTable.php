<?php

namespace App\Filament\Resources\LegalInstruments\Tables;

use App\Enums\InstrumentKind;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class LegalInstrumentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('date', 'desc')
            ->columns([
                TextColumn::make('kind')->badge()->sortable(),
                TextColumn::make('number')->searchable()->sortable(),
                TextColumn::make('date')->date()->sortable(),
                TextColumn::make('title_en')->label('Title')->searchable(['title_en', 'title_ar'])->limit(60)->description(fn ($record) => $record->title_ar),
                IconColumn::make('in_force')->boolean(),
                TextColumn::make('gazette_issue')->label('OG issue')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('kind')->options(InstrumentKind::class),
                TernaryFilter::make('in_force'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
