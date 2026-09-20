<?php

namespace App\Filament\Resources\Bodies\Schemas;

use App\Enums\BodyStatus;
use App\Enums\BodySubtype;
use App\Enums\BodyType;
use App\Enums\LegalForm;
use App\Enums\Sector;
use App\Filament\Support\Review;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BodyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Identity')
                    ->columns(2)
                    ->schema([
                        TextInput::make('slug')->required()->maxLength(160)->unique(ignoreRecord: true)->helperText('lb-… stable public id'),
                        Select::make('type')->options(BodyType::class)->required(),
                        Select::make('subtype')->options(BodySubtype::class)->searchable(),
                        Select::make('legal_form')->options(LegalForm::class)->searchable(),
                        Select::make('sector')->options(Sector::class)->required(),
                        Select::make('status')->options(BodyStatus::class)->default('active')->required(),
                        Textarea::make('status_note')->rows(2)->columnSpanFull(),
                    ]),
                Section::make('Names')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name_ar')->label('Name (ar)')->required()->extraInputAttributes(['dir' => 'rtl']),
                        TextInput::make('name_en')->label('Name (en)')->required(),
                        TextInput::make('name_fr')->label('Name (fr)'),
                        TagsInput::make('aliases.ar')->label('Aliases (ar)')->placeholder('add alias'),
                        TagsInput::make('aliases.en')->label('Aliases (en)')->placeholder('add alias'),
                        TagsInput::make('aliases.fr')->label('Aliases (fr)')->placeholder('add alias'),
                        Textarea::make('description_ar')->label('Description (ar)')->rows(5)->extraInputAttributes(['dir' => 'rtl'])->columnSpanFull(),
                        Textarea::make('description_en')->label('Description (en)')->rows(5)->columnSpanFull(),
                    ]),
                Section::make('Structure')
                    ->columns(2)
                    ->schema([
                        Select::make('parent_id')->label('Parent body')->relationship('parent', 'name_en')->searchable(['name_en', 'name_ar', 'slug'])->preload(),
                        TextInput::make('level')->numeric()->default(0),
                        Select::make('legal_instrument_id')->label('Creating instrument')->relationship('legalInstrument', 'title_en')->searchable(['title_en', 'number'])->preload(),
                        TextInput::make('official_url')->url(),
                        TextInput::make('seats_count')->numeric()->default(0),
                        Toggle::make('state_funded')->label('State funded (confessional courts)')->inline(false),
                        TagsInput::make('functions')->placeholder('audit, jurisdiction …'),
                        KeyValue::make('ownership')->keyLabel('field')->valueLabel('value')->helperText('owner_body_slug, share'),
                        KeyValue::make('layout_hints')->keyLabel('hint')->valueLabel('value')->helperText('band, pill, ring overrides'),
                    ]),
                Review::section(),
            ]);
    }
}
