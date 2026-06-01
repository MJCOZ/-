<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('genre_title', function (Blueprint $table) {
            $table->id();
            $table->foreignId('genre_id')->constrained()->cascadeOnDelete();
            $table->foreignId('title_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['genre_id', 'title_id']);
        });

        // نقل التصنيف الأساسي الحالي إلى جدول الربط
        DB::table('titles')->whereNotNull('genre_id')->orderBy('id')->each(function ($title) {
            DB::table('genre_title')->insert([
                'genre_id' => $title->genre_id,
                'title_id' => $title->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genre_title');
    }
};
