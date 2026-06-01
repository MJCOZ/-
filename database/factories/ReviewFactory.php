<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\Title;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title_id' => Title::factory(),
            'rating' => fake()->numberBetween(1, 5),
            'body' => fake()->paragraph(),
        ];
    }
}
