<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bodies', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 160)->unique();
            $table->string('type', 30)->index();
            $table->string('subtype', 40)->nullable()->index();
            $table->string('legal_form', 40)->nullable();
            $table->string('sector', 20)->index();
            $table->foreignId('parent_id')->nullable()->constrained('bodies')->nullOnDelete();
            $table->unsignedSmallInteger('level')->default(0);
            $table->text('name_ar');
            $table->text('name_en');
            $table->text('name_fr')->nullable();
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->jsonb('aliases')->nullable();
            $table->text('official_url')->nullable();
            $table->foreignId('legal_instrument_id')->nullable()->constrained('legal_instruments')->nullOnDelete();
            $table->unsignedInteger('seats_count')->default(0);
            $table->jsonb('functions')->nullable();
            $table->boolean('state_funded')->nullable();
            $table->jsonb('ownership')->nullable();
            $table->string('status', 30)->default('active')->index();
            $table->text('status_note')->nullable();
            $table->jsonb('layout_hints')->nullable();
            $table->string('review_state', 20)->default('draft')->index();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });

        Schema::table('legal_instruments', function (Blueprint $table) {
            $table->foreign('issuer_body_id')->references('id')->on('bodies')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('legal_instruments', function (Blueprint $table) {
            $table->dropForeign(['issuer_body_id']);
        });
        Schema::dropIfExists('bodies');
    }
};
