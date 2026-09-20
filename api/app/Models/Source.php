<?php

namespace App\Models;

use App\Enums\SourceKind;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Source extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'kind' => SourceKind::class,
            'published_at' => 'date',
            'fetched_at' => 'datetime',
        ];
    }

    public function sourceable(): MorphTo
    {
        return $this->morphTo();
    }
}
