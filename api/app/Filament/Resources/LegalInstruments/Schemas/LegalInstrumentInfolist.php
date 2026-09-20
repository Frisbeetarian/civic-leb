<?php

namespace App\Filament\Resources\LegalInstruments\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class LegalInstrumentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('kind')
                    ->badge(),
                TextEntry::make('number')
                    ->placeholder('-'),
                TextEntry::make('date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('title_ar')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('title_en')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('issuer_body_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('gazette_issue')
                    ->placeholder('-'),
                TextEntry::make('gazette_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('gazette_url')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('source_url')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('text_url')
                    ->placeholder('-')
                    ->columnSpanFull(),
                IconEntry::make('in_force')
                    ->boolean(),
                TextEntry::make('annulledBy.id')
                    ->label('Annulled by')
                    ->placeholder('-'),
                TextEntry::make('supersededBy.id')
                    ->label('Superseded by')
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
