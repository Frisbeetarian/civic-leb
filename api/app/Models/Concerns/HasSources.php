<?php

namespace App\Models\Concerns;

use App\Models\Source;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasSources
{
    public function sources(): MorphMany
    {
        return $this->morphMany(Source::class, 'sourceable');
    }
}
