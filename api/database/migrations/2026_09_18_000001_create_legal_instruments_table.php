<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('legal_instruments', function (Blueprint $table) {
            $table->id();
            $table->string('kind', 40)->index();
            $table->string('number', 60)->nullable();
            $table->date('date')->nullable();
            $table->text('title_ar')->nullable();
            $table->text('title_en')->nullable();
            $table->unsignedBigInteger('issuer_body_id')->nullable()->index();
            $table->jsonb('signatories')->nullable();
            $table->string('gazette_issue', 60)->nullable();
            $table->date('gazette_date')->nullable();
            $table->text('gazette_url')->nullable();
            $table->text('source_url')->nullable();
            $table->text('text_url')->nullable();
            $table->boolean('in_force')->default(true);
            $table->foreignId('annulled_by_id')->nullable()->constrained('legal_instruments')->nullOnDelete();
            $table->foreignId('superseded_by_id')->nullable()->constrained('legal_instruments')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['kind', 'number', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('legal_instruments');
    }
};
