<?php

namespace App\Models;

use App\Enums\ReviewState;
use App\Models\Concerns\HasReviewState;
use App\Models\Concerns\HasSources;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    use HasReviewState;
    use HasSources;

    protected $table = 'persons';

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'name_variants' => 'array',
            'review_state' => ReviewState::class,
            'reviewed_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }

    public function tenures(): HasMany
    {
        return $this->hasMany(Tenure::class)->orderByDesc('start_date');
    }
}
