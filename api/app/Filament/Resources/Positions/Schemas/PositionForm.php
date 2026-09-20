<?php

namespace App\Filament\Resources\Positions\Schemas;

use App\Enums\Confession;
use App\Enums\ConfessionBasis;
use App\Enums\Grade;
use App\Enums\PositionKind;
use App\Enums\PositionStatus;
use App\Filament\Support\Review;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PositionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Identity')
                    ->columns(2)
                    ->schema([
                        TextInput::make('slug')->required()->maxLength(160)->unique(ignoreRecord: true),
                        Select::make('body_id')->label('Body')->relationship('body', 'name_en')->searchable(['name_en', 'name_ar', 'slug'])->preload()->required(),
                        Select::make('kind')->options(PositionKind::class)->required(),
                        Toggle::make('is_graph_node')->label('Render as graph node')->default(true)->inline(false),
                        Select::make('status')->options(PositionStatus::class)->default('active')->required(),
                        TextInput::make('sort_order')->numeric()->default(0),
                    ]),
                Section::make('Titles')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title_ar')->label('Title (ar)')->required()->extraInputAttributes(['dir' => 'rtl']),
                        TextInput::make('title_en')->label('Title (en)')->required(),
                        TextInput::make('title_fr')->label('Title (fr)'),
                        Textarea::make('description_ar')->label('Description (ar)')->rows(4)->extraInputAttributes(['dir' => 'rtl'])->columnSpanFull(),
                        Textarea::make('description_en')->label('Description (en)')->rows(4)->columnSpanFull(),
                    ]),
                Section::make('Appointment and allocation')
                    ->columns(2)
                    ->schema([
                        Select::make('grade')->options(Grade::class),
                        Select::make('appointing_authority_id')->label('Appointing authority')->relationship('appointingAuthority', 'name_en')->searchable(['name_en', 'name_ar'])->preload(),
                        TextInput::make('term_years')->numeric(),
                        Select::make('legal_instrument_id')->label('Creating instrument')->relationship('legalInstrument', 'title_en')->searchable(['title_en', 'number'])->preload(),
                        Select::make('confession')->options(Confession::class)->searchable(),
                        Select::make('confession_basis')->options(ConfessionBasis::class)
                            ->helperText('constitution: the 128 seats only; pact: President, PM, Speaker, Deputy Speaker, Deputy PM; custom: everything else, with a source'),
                        TextInput::make('confession_source_url')->url()->columnSpanFull()
                            ->requiredIf('confession_basis', ConfessionBasis::Custom->value),
                    ]),
                Section::make('Parliamentary seat')
                    ->columns(3)
                    ->description('Only for kind = seat. Law 44/2017 Annex 1.')
                    ->schema([
                        TextInput::make('seat_major_district'),
                        TextInput::make('seat_minor_district'),
                        TextInput::make('seat_ordinal')->numeric(),
                    ]),
                Review::section(),
            ]);
    }
}
