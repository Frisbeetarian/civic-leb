<?php

namespace App\Models;

use App\Enums\Confession;
use App\Enums\ConfessionBasis;
use App\Enums\Grade;
use App\Enums\PositionKind;
use App\Enums\PositionStatus;
use App\Enums\ReviewState;
use App\Models\Concerns\HasReviewState;
use App\Models\Concerns\HasSources;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Position extends Model
{
    use HasReviewState;
    use HasSources;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'kind' => PositionKind::class,
            'grade' => Grade::class,
            'confession' => Confession::class,
            'confession_basis' => ConfessionBasis::class,
            'status' => PositionStatus::class,
            'review_state' => ReviewState::class,
            'is_graph_node' => 'boolean',
            'reviewed_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function body(): BelongsTo
    {
        return $this->belongsTo(Body::class);
    }

    public function appointingAuthority(): BelongsTo
    {
        return $this->belongsTo(Body::class, 'appointing_authority_id');
    }

    public function legalInstrument(): BelongsTo
    {
        return $this->belongsTo(LegalInstrument::class);
    }

    public function tenures(): HasMany
    {
        return $this->hasMany(Tenure::class)->orderByDesc('start_date');
    }

    public function currentTenure(): HasOne
    {
        return $this->hasOne(Tenure::class)->whereNull('end_date')->latestOfMany('start_date');
    }

    public function outgoingEdges(): MorphMany
    {
        return $this->morphMany(Edge::class, 'from');
    }

    public function incomingEdges(): MorphMany
    {
        return $this->morphMany(Edge::class, 'to');
    }
}
