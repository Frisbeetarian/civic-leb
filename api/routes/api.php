<?php

use App\Http\Controllers\Api\GraphController;
use Illuminate\Support\Facades\Route;

Route::prefix('lb')->name('api.lb.')->group(function () {
    Route::get('graph', [GraphController::class, 'show'])->name('graph');
    Route::get('graph/version', [GraphController::class, 'version'])->name('graph.version');
    Route::get('layout', [GraphController::class, 'layout'])->name('layout');
    Route::get('nodes/{slug}', [GraphController::class, 'node'])->name('node');
});
