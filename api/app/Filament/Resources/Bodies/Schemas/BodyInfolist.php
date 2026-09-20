<?php

namespace App\Filament\Resources\Bodies\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BodyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('slug'),
                TextEntry::make('type')
                    ->badge(),
                TextEntry::make('subtype')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('legal_form')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('sector')
                    ->badge(),
                TextEntry::make('parent.id')
                    ->label('Parent')
                    ->placeholder('-'),
                TextEntry::make('level')
                    ->numeric(),
                TextEntry::make('name_ar')
                    ->columnSpanFull(),
                TextEntry::make('name_en')
                    ->columnSpanFull(),
                TextEntry::make('name_fr')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('description_ar')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('description_en')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('official_url')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('legalInstrument.id')
                    ->label('Legal instrument')
                    ->placeholder('-'),
                TextEntry::make('seats_count')
                    ->numeric(),
                IconEntry::make('state_funded')
                    ->boolean()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('status_note')
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
