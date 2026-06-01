<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('titles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('genre_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->enum('type', ['movie', 'series'])->default('movie');
            $table->text('description')->nullable();
            $table->string('poster')->nullable();
            $table->unsignedSmallInteger('release_year')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titles');
    }
};
