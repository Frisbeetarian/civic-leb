<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 160)->unique();
            $table->text('name_ar');
            $table->text('name_en');
            $table->text('name_fr')->nullable();
            $table->jsonb('name_variants')->nullable();
            $table->string('party', 120)->nullable();
            $table->string('wikidata_qid', 20)->nullable()->unique();
            $table->text('portrait_path')->nullable();
            $table->string('portrait_licence', 80)->nullable();
            $table->text('portrait_attribution')->nullable();
            $table->text('portrait_source_url')->nullable();
            $table->string('review_state', 20)->default('draft')->index();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};
