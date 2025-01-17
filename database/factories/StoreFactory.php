<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake('ar_SA')->company(),
            'logo' => fake()->imageUrl(),
            'address' => fake('ar_SA')->address(),
            'location'=>json_encode(fake('ar_SA')->localCoordinates()),
        ];
    }
}
