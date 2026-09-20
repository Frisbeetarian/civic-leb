<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 160)->unique();
            $table->foreignId('body_id')->constrained('bodies')->cascadeOnDelete();
            $table->string('kind', 30)->index();
            $table->boolean('is_graph_node')->default(true);
            $table->text('title_ar');
            $table->text('title_en');
            $table->text('title_fr')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->string('grade', 20)->nullable();
            $table->foreignId('appointing_authority_id')->nullable()->constrained('bodies')->nullOnDelete();
            $table->string('confession', 30)->nullable()->index();
            $table->string('confession_basis', 20)->nullable();
            $table->text('confession_source_url')->nullable();
            $table->string('seat_major_district', 80)->nullable();
            $table->string('seat_minor_district', 80)->nullable();
            $table->unsignedSmallInteger('seat_ordinal')->nullable();
            $table->unsignedSmallInteger('term_years')->nullable();
            $table->string('status', 30)->default('active')->index();
            $table->foreignId('legal_instrument_id')->nullable()->constrained('legal_instruments')->nullOnDelete();
            $table->integer('sort_order')->default(0);
            $table->string('review_state', 20)->default('draft')->index();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['body_id', 'kind']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
