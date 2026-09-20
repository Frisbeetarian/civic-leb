<?php

namespace App\Filament\Support;

use App\Enums\ReviewState;
use App\Services\Graph\GraphExporter;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Shared review lifecycle UI: draft -> reviewed -> published (decisions.md Q28).
 */
class Review
{
    public static function section(): Section
    {
        return Section::make('Review')
            ->columns(3)
            ->schema([
                Select::make('review_state')
                    ->options(ReviewState::class)
                    ->default(ReviewState::Draft)
                    ->required()
                    ->disabled()
                    ->dehydrated(false)
                    ->helperText('Use the Mark reviewed / Publish actions to change the state.'),
            ]);
    }

    public static function column(): TextColumn
    {
        return TextColumn::make('review_state')
            ->badge()
            ->color(fn (ReviewState $state) => match ($state) {
                ReviewState::Draft => 'gray',
                ReviewState::Reviewed => 'warning',
                ReviewState::Published => 'success',
            })
            ->sortable();
    }

    public static function filter(): SelectFilter
    {
        return SelectFilter::make('review_state')->options(ReviewState::class);
    }

    /** @return array<Action> */
    public static function recordActions(): array
    {
        return [
            Action::make('markReviewed')
                ->label('Mark reviewed')
                ->icon('heroicon-o-check')
                ->color('warning')
                ->visible(fn (Model $record) => $record->review_state === ReviewState::Draft)
                ->action(fn (Model $record) => self::transition($record, ReviewState::Reviewed)),
            Action::make('publish')
                ->label('Publish')
                ->icon('heroicon-o-globe-alt')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (Model $record) => $record->review_state === ReviewState::Reviewed)
                ->action(fn (Model $record) => self::transition($record, ReviewState::Published)),
            Action::make('unpublish')
                ->label('Unpublish')
                ->icon('heroicon-o-eye-slash')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (Model $record) => $record->review_state === ReviewState::Published)
                ->action(fn (Model $record) => self::transition($record, ReviewState::Reviewed)),
        ];
    }

    /** @return array<BulkAction> */
    public static function bulkActions(): array
    {
        return [
            BulkAction::make('markReviewedBulk')
                ->label('Mark reviewed')
                ->icon('heroicon-o-check')
                ->action(fn (Collection $records) => $records->each(fn (Model $r) => $r->review_state === ReviewState::Draft && self::transition($r, ReviewState::Reviewed)))
                ->deselectRecordsAfterCompletion(),
            BulkAction::make('publishBulk')
                ->label('Publish')
                ->icon('heroicon-o-globe-alt')
                ->requiresConfirmation()
                ->action(fn (Collection $records) => $records->each(fn (Model $r) => $r->review_state === ReviewState::Reviewed && self::transition($r, ReviewState::Published)))
                ->deselectRecordsAfterCompletion(),
        ];
    }

    public static function transition(Model $record, ReviewState $to): void
    {
        $record->review_state = $to;
        if ($to === ReviewState::Reviewed) {
            $record->reviewed_at ??= now();
        }
        if ($to === ReviewState::Published) {
            $record->reviewed_at ??= now();
            $record->published_at = now();
        }
        $record->save();
        app(GraphExporter::class)->invalidate();
    }
}
