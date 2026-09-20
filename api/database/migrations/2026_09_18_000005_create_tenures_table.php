<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('position_id')->constrained('positions')->cascadeOnDelete();
            $table->foreignId('person_id')->nullable()->constrained('persons')->nullOnDelete();
            $table->string('status', 30)->index();
            $table->string('vacancy_reason', 30)->nullable();
            $table->date('decision_date')->nullable();
            $table->date('instrument_date')->nullable();
            $table->date('effective_date')->nullable();
            $table->date('start_date')->index();
            $table->date('end_date')->nullable()->index();
            $table->string('end_reason', 30)->nullable();
            $table->foreignId('predecessor_tenure_id')->nullable()->constrained('tenures')->nullOnDelete();
            $table->foreignId('legal_instrument_id')->nullable()->constrained('legal_instruments')->nullOnDelete();
            $table->string('bloc', 120)->nullable();
            $table->string('party', 120)->nullable();
            $table->text('notes')->nullable();
            $table->string('review_state', 20)->default('draft')->index();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['position_id', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenures');
    }
};
