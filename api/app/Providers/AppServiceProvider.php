<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Relation::enforceMorphMap([
            'body' => \App\Models\Body::class,
            'position' => \App\Models\Position::class,
            'person' => \App\Models\Person::class,
            'tenure' => \App\Models\Tenure::class,
            'edge' => \App\Models\Edge::class,
            'legal_instrument' => \App\Models\LegalInstrument::class,
        ]);
        //
    }
}
