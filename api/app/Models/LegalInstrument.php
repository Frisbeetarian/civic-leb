<?php

namespace App\Models;

use App\Enums\InstrumentKind;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegalInstrument extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'kind' => InstrumentKind::class,
            'date' => 'date',
            'gazette_date' => 'date',
            'signatories' => 'array',
            'in_force' => 'boolean',
        ];
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(Body::class, 'issuer_body_id');
    }

    public function annulledBy(): BelongsTo
    {
        return $this->belongsTo(self::class, 'annulled_by_id');
    }

    public function supersededBy(): BelongsTo
    {
        return $this->belongsTo(self::class, 'superseded_by_id');
    }

    public function label(): string
    {
        $parts = array_filter([$this->kind?->value, $this->number, $this->date?->format('Y')]);

        return $this->title_en ?: implode(' ', $parts);
    }
}
