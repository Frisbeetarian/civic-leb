<?php

namespace App\Filament\Resources\Bodies\Tables;

use App\Enums\BodyStatus;
use App\Enums\BodySubtype;
use App\Enums\BodyType;
use App\Enums\Sector;
use App\Filament\Support\Review;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BodiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('name_en')
            ->columns([
                TextColumn::make('name_en')->label('Name')->searchable(['name_en', 'name_ar', 'slug'])->sortable()->description(fn ($record) => $record->name_ar),
                TextColumn::make('type')->badge()->sortable(),
                TextColumn::make('subtype')->badge()->sortable()->toggleable(),
                TextColumn::make('sector')->badge()->sortable(),
                TextColumn::make('parent.name_en')->label('Parent')->toggleable(),
                TextColumn::make('status')->badge()->sortable(),
                Review::column(),
                TextColumn::make('updated_at')->since()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')->options(BodyType::class),
                SelectFilter::make('subtype')->options(BodySubtype::class),
                SelectFilter::make('sector')->options(Sector::class),
                SelectFilter::make('status')->options(BodyStatus::class),
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
