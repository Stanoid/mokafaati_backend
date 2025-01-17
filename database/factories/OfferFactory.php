<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer>
 */
class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'store_id' => fake()->numberBetween(1,3),
            'start_date' => fake()->dateTimeThisMonth(),
            'end_date' => fake()->dateTimeBetween('now', '+4 days'),
            'title' => fake('ar_SA')->realText(),
            'cash_back'=>fake()->numberBetween(3,15),
        ];
    }
}
