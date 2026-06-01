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
        Schema::table('titles', function (Blueprint $table) {
            $table->decimal('imdb_rating', 3, 1)->nullable()->after('poster');      // 0.0 - 10.0
            $table->unsignedTinyInteger('rt_rating')->nullable()->after('imdb_rating'); // 0 - 100 %
            $table->unsignedTinyInteger('personal_rating')->nullable()->after('rt_rating'); // 1 - 10 (تقييمي)
            $table->boolean('watched')->default(false)->after('personal_rating');
            $table->date('watched_at')->nullable()->after('watched');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('titles', function (Blueprint $table) {
            $table->dropColumn(['imdb_rating', 'rt_rating', 'personal_rating', 'watched', 'watched_at']);
        });
    }
};
