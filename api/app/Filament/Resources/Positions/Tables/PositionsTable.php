<?php

namespace App\Filament\Resources\Positions\Tables;

use App\Enums\Confession;
use App\Enums\PositionKind;
use App\Enums\PositionStatus;
use App\Filament\Support\Review;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PositionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('title_en')
            ->columns([
                TextColumn::make('title_en')->label('Title')->searchable(['title_en', 'title_ar', 'slug'])->sortable()->description(fn ($record) => $record->title_ar),
                TextColumn::make('body.name_en')->label('Body')->searchable()->sortable(),
                TextColumn::make('kind')->badge()->sortable(),
                TextColumn::make('confession')->badge()->toggleable(),
                TextColumn::make('grade')->badge()->toggleable(),
                TextColumn::make('currentTenure.person.name_en')->label('Current holder')->placeholder('vacant / unknown'),
                IconColumn::make('is_graph_node')->boolean()->label('Node')->toggleable(),
                TextColumn::make('status')->badge()->sortable(),
                Review::column(),
            ])
            ->filters([
                SelectFilter::make('kind')->options(PositionKind::class),
                SelectFilter::make('confession')->options(Confession::class),
                SelectFilter::make('status')->options(PositionStatus::class),
                SelectFilter::make('body_id')->label('Body')->relationship('body', 'name_en')->searchable()->preload(),
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
