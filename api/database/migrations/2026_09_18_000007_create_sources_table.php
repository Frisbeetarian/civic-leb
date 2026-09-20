<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sources', function (Blueprint $table) {
            $table->id();
            $table->string('sourceable_type', 40);
            $table->unsignedBigInteger('sourceable_id');
            $table->string('kind', 30)->index();
            $table->text('url');
            $table->text('title')->nullable();
            $table->string('publisher', 160)->nullable();
            $table->date('published_at')->nullable();
            $table->timestamp('fetched_at')->nullable();
            $table->text('reliability_note')->nullable();
            $table->text('excerpt')->nullable();
            $table->timestamps();

            $table->index(['sourceable_type', 'sourceable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sources');
    }
};
