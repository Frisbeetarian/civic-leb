<?php

namespace App\Filament\Resources\Positions\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PositionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('slug'),
                TextEntry::make('body.id')
                    ->label('Body'),
                TextEntry::make('kind')
                    ->badge(),
                IconEntry::make('is_graph_node')
                    ->boolean(),
                TextEntry::make('title_ar')
                    ->columnSpanFull(),
                TextEntry::make('title_en')
                    ->columnSpanFull(),
                TextEntry::make('title_fr')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('description_ar')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('description_en')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('grade')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('appointingAuthority.id')
                    ->label('Appointing authority')
                    ->placeholder('-'),
                TextEntry::make('confession')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('confession_basis')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('confession_source_url')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('seat_major_district')
                    ->placeholder('-'),
                TextEntry::make('seat_minor_district')
                    ->placeholder('-'),
                TextEntry::make('seat_ordinal')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('term_years')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('legalInstrument.id')
                    ->label('Legal instrument')
                    ->placeholder('-'),
                TextEntry::make('sort_order')
                    ->numeric(),
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
