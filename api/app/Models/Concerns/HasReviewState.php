<?php

namespace App\Models\Concerns;

use App\Enums\ReviewState;
use Illuminate\Database\Eloquent\Builder;

trait HasReviewState
{
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('review_state', ReviewState::Published->value);
    }

    public function isPublished(): bool
    {
        return $this->review_state === ReviewState::Published;
    }
}
