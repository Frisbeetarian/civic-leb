<?php

namespace App\Filament\Resources\Edges\Schemas;

use App\Enums\EdgeType;
use App\Filament\Support\Review;
use App\Models\Body;
use App\Models\Position;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class EdgeForm
{
    public static function configure(Schema $schema): Schema
    {
        $types = fn () => [
            MorphToSelect\Type::make(Body::class)->titleAttribute('name_en')->label('Body')->searchColumns(['name_en', 'name_ar', 'slug']),
            MorphToSelect\Type::make(Position::class)->titleAttribute('title_en')->label('Position')->searchColumns(['title_en', 'title_ar', 'slug']),
        ];

        return $schema
            ->columns(1)
            ->components([
                Section::make('Relationship')
                    ->columns(2)
                    ->schema([
                        Select::make('type')->options(EdgeType::class)->required()->columnSpanFull(),
                        MorphToSelect::make('from')->label('From')->types($types())->searchable()->preload()->required(),
                        MorphToSelect::make('to')->label('To')->types($types())->searchable()->preload()->required(),
                        TextInput::make('seats_appointed')->numeric()->default(0),
                        Select::make('legal_instrument_id')->label('Legal basis')->relationship('legalInstrument', 'title_en')->searchable(['title_en', 'number'])->preload(),
                        KeyValue::make('metadata')->keyLabel('key')->valueLabel('value')->columnSpanFull()
                            ->helperText('kind (oversees), share (owns), outcome (reviews), role (ex_officio), instrument_type / proposer / signatories / majority (appoints), cite, note'),
                    ]),
                Review::section(),
            ]);
    }
}
