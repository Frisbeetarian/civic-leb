<?php

namespace App\Filament\Resources\People\Schemas;

use App\Filament\Support\Review;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PersonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Names')
                    ->columns(2)
                    ->schema([
                        TextInput::make('slug')->required()->maxLength(160)->unique(ignoreRecord: true),
                        TextInput::make('wikidata_qid')->label('Wikidata QID')->maxLength(20),
                        TextInput::make('name_ar')->label('Name (ar)')->required()->extraInputAttributes(['dir' => 'rtl']),
                        TextInput::make('name_en')->label('Name (en)')->required(),
                        TextInput::make('name_fr')->label('Name (fr)'),
                        TextInput::make('party'),
                        TagsInput::make('name_variants')->label('Transliteration variants')->columnSpanFull(),
                    ]),
                Section::make('Portrait')
                    ->columns(2)
                    ->description('Self-hosted with licence and attribution (decisions.md Q13).')
                    ->schema([
                        TextInput::make('portrait_path'),
                        TextInput::make('portrait_licence'),
                        TextInput::make('portrait_source_url')->url(),
                        Textarea::make('portrait_attribution')->rows(2),
                    ]),
                Review::section(),
            ]);
    }
}
