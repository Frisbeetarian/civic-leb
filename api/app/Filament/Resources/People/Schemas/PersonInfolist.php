<?php

namespace App\Filament\Resources\People\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PersonInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('slug'),
                TextEntry::make('name_ar')
                    ->columnSpanFull(),
                TextEntry::make('name_en')
                    ->columnSpanFull(),
                TextEntry::make('name_fr')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('party')
                    ->placeholder('-'),
                TextEntry::make('wikidata_qid')
                    ->placeholder('-'),
                TextEntry::make('portrait_path')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('portrait_licence')
                    ->placeholder('-'),
                TextEntry::make('portrait_attribution')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('portrait_source_url')
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
