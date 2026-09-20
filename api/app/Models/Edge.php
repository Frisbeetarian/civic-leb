<?php

namespace App\Models;

use App\Enums\EdgeType;
use App\Enums\ReviewState;
use App\Models\Concerns\HasReviewState;
use App\Models\Concerns\HasSources;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Edge extends Model
{
    use HasReviewState;
    use HasSources;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'type' => EdgeType::class,
            'metadata' => 'array',
            'review_state' => ReviewState::class,
            'reviewed_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function from(): MorphTo
    {
        return $this->morphTo();
    }

    public function to(): MorphTo
    {
        return $this->morphTo();
    }

    public function legalInstrument(): BelongsTo
    {
        return $this->belongsTo(LegalInstrument::class);
    }
}
