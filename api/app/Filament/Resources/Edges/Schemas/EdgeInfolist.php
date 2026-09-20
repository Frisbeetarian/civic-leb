<?php

namespace App\Filament\Resources\Edges\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EdgeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('type')
                    ->badge(),
                TextEntry::make('from_type'),
                TextEntry::make('from_id')
                    ->numeric(),
                TextEntry::make('to_type'),
                TextEntry::make('to_id')
                    ->numeric(),
                TextEntry::make('seats_appointed')
                    ->numeric(),
                TextEntry::make('legalInstrument.id')
                    ->label('Legal instrument')
                    ->placeholder('-'),
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
