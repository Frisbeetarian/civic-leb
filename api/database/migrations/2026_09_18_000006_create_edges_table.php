<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('edges', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30)->index();
            $table->string('from_type', 20);
            $table->unsignedBigInteger('from_id');
            $table->string('to_type', 20);
            $table->unsignedBigInteger('to_id');
            $table->unsignedInteger('seats_appointed')->default(0);
            $table->jsonb('metadata')->nullable();
            $table->foreignId('legal_instrument_id')->nullable()->constrained('legal_instruments')->nullOnDelete();
            $table->string('review_state', 20)->default('draft')->index();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['from_type', 'from_id']);
            $table->index(['to_type', 'to_id']);
            $table->unique(['type', 'from_type', 'from_id', 'to_type', 'to_id'], 'edges_unique_relation');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('edges');
    }
};
