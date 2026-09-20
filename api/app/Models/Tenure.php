<?php

namespace App\Models;

use App\Enums\EndReason;
use App\Enums\ReviewState;
use App\Enums\TenureStatus;
use App\Enums\VacancyReason;
use App\Models\Concerns\HasReviewState;
use App\Models\Concerns\HasSources;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tenure extends Model
{
    use HasReviewState;
    use HasSources;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'status' => TenureStatus::class,
            'vacancy_reason' => VacancyReason::class,
            'end_reason' => EndReason::class,
            'review_state' => ReviewState::class,
            'decision_date' => 'date',
            'instrument_date' => 'date',
            'effective_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
            'reviewed_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Tenure $tenure) {
            $tenure->start_date ??= $tenure->effective_date ?? $tenure->instrument_date ?? $tenure->decision_date;
            if ($tenure->person_id === null) {
                $tenure->status = TenureStatus::Vacant;
            }
        });
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class);
    }

    public function predecessor(): BelongsTo
    {
        return $this->belongsTo(self::class, 'predecessor_tenure_id');
    }

    public function legalInstrument(): BelongsTo
    {
        return $this->belongsTo(LegalInstrument::class);
    }

    public function isVacancy(): bool
    {
        return $this->person_id === null;
    }
}
