<?php

namespace App\Filament\Resources\Tenures\Schemas;

use App\Enums\EndReason;
use App\Enums\TenureStatus;
use App\Enums\VacancyReason;
use App\Filament\Support\Review;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TenureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Who and where')
                    ->columns(2)
                    ->schema([
                        Select::make('position_id')->label('Position')->relationship('position', 'title_en')->searchable(['title_en', 'title_ar', 'slug'])->preload()->required(),
                        Select::make('person_id')->label('Person')->relationship('person', 'name_en')->searchable(['name_en', 'name_ar'])->preload()
                            ->helperText('Leave empty to record a vacancy.'),
                        Select::make('status')->options(TenureStatus::class)->required(),
                        Select::make('vacancy_reason')->options(VacancyReason::class),
                        TextInput::make('bloc'),
                        TextInput::make('party'),
                    ]),
                Section::make('Dates and instrument')
                    ->columns(3)
                    ->description('decision (cabinet or board), instrument (decree signature), effective (oath or assumption).')
                    ->schema([
                        DatePicker::make('decision_date'),
                        DatePicker::make('instrument_date'),
                        DatePicker::make('effective_date'),
                        DatePicker::make('start_date')->helperText('Defaults to effective, then instrument, then decision.'),
                        DatePicker::make('end_date'),
                        Select::make('end_reason')->options(EndReason::class),
                        Select::make('legal_instrument_id')->label('Appointing instrument')->relationship('legalInstrument', 'title_en')->searchable(['title_en', 'number'])->preload()->columnSpan(2),
                        Select::make('predecessor_tenure_id')->label('Predecessor tenure')->relationship('predecessor', 'id')->searchable()
                            ->getOptionLabelFromRecordUsing(fn ($record) => ($record->person?->name_en ?? 'vacancy').' · '.$record->position?->title_en.' · '.$record->start_date?->format('Y-m-d')),
                    ]),
                Textarea::make('notes')->rows(3),
                Review::section(),
            ]);
    }
}
