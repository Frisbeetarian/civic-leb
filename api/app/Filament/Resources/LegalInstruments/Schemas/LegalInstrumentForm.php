<?php

namespace App\Filament\Resources\LegalInstruments\Schemas;

use App\Enums\InstrumentKind;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LegalInstrumentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Instrument')
                    ->columns(3)
                    ->schema([
                        Select::make('kind')->options(InstrumentKind::class)->required(),
                        TextInput::make('number'),
                        DatePicker::make('date'),
                        TextInput::make('title_ar')->label('Title (ar)')->extraInputAttributes(['dir' => 'rtl'])->columnSpan(3),
                        TextInput::make('title_en')->label('Title (en)')->columnSpan(3),
                        Select::make('issuer_body_id')->label('Issuer')->relationship('issuer', 'name_en')->searchable(['name_en', 'name_ar'])->preload(),
                        TagsInput::make('signatories')->placeholder('position or body slug')->columnSpan(2),
                    ]),
                Section::make('Publication and validity')
                    ->columns(3)
                    ->schema([
                        TextInput::make('gazette_issue'),
                        DatePicker::make('gazette_date'),
                        TextInput::make('gazette_url')->url(),
                        TextInput::make('source_url')->url()->columnSpan(2),
                        TextInput::make('text_url')->url(),
                        Toggle::make('in_force')->default(true)->inline(false),
                        Select::make('annulled_by_id')->label('Annulled by')->relationship('annulledBy', 'title_en')->searchable(['title_en', 'number'])->preload(),
                        Select::make('superseded_by_id')->label('Superseded by')->relationship('supersededBy', 'title_en')->searchable(['title_en', 'number'])->preload(),
                        Textarea::make('notes')->rows(3)->columnSpan(3),
                    ]),
            ]);
    }
}
