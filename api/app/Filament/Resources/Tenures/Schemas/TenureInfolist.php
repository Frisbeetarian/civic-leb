<?php

namespace App\Filament\Resources\Tenures\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TenureInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('position.id')
                    ->label('Position'),
                TextEntry::make('person.id')
                    ->label('Person')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('vacancy_reason')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('decision_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('instrument_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('effective_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('start_date')
                    ->date(),
                TextEntry::make('end_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('end_reason')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('predecessor_tenure_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('legalInstrument.id')
                    ->label('Legal instrument')
                    ->placeholder('-'),
                TextEntry::make('bloc')
                    ->placeholder('-'),
                TextEntry::make('party')
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('review_state')
                    ->badge(),
                TextEntry::make('reviewed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('published_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
