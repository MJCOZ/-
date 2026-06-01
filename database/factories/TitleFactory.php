<?php

namespace Database\Factories;

use App\Models\Genre;
use App\Models\Title;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Title>
 */
class TitleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'genre_id' => Genre::factory(),
            'name' => fake()->sentence(3),
            'type' => fake()->randomElement(['movie', 'series']),
            'description' => fake()->paragraph(),
            'poster' => null,
            'release_year' => fake()->numberBetween(1980, 2025),
            'watched' => false,
        ];
    }

    public function watched(): static
    {
        return $this->state(fn () => [
            'watched' => true,
            'watched_at' => now()->subDays(fake()->numberBetween(1, 100)),
            'imdb_rating' => fake()->randomFloat(1, 5, 9.5),
            'rt_rating' => fake()->numberBetween(50, 99),
            'personal_rating' => fake()->numberBetween(6, 10),
        ]);
    }

    public function movie(): static
    {
        return $this->state(fn () => ['type' => 'movie']);
    }

    public function series(): static
    {
        return $this->state(fn () => ['type' => 'series']);
    }
}
