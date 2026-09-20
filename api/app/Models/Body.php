<?php

namespace App\Models;

use App\Enums\BodyStatus;
use App\Enums\BodySubtype;
use App\Enums\BodyType;
use App\Enums\LegalForm;
use App\Enums\ReviewState;
use App\Enums\Sector;
use App\Models\Concerns\HasReviewState;
use App\Models\Concerns\HasSources;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Body extends Model
{
    use HasReviewState;
    use HasSources;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'type' => BodyType::class,
            'subtype' => BodySubtype::class,
            'legal_form' => LegalForm::class,
            'sector' => Sector::class,
            'status' => BodyStatus::class,
            'review_state' => ReviewState::class,
            'aliases' => 'array',
            'functions' => 'array',
            'ownership' => 'array',
            'layout_hints' => 'array',
            'state_funded' => 'boolean',
            'reviewed_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function positions(): HasMany
    {
        return $this->hasMany(Position::class);
    }

    public function legalInstrument(): BelongsTo
    {
        return $this->belongsTo(LegalInstrument::class);
    }

    public function outgoingEdges(): MorphMany
    {
        return $this->morphMany(Edge::class, 'from');
    }

    public function incomingEdges(): MorphMany
    {
        return $this->morphMany(Edge::class, 'to');
    }

    public function head(): ?Position
    {
        return $this->positions->firstWhere('kind', \App\Enums\PositionKind::Head);
    }
}
