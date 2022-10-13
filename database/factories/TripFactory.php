<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'car_id' => Car::factory()->create()->id,
            'date' => $this->faker->date(),
            'miles' => $this->faker->randomFloat(2, 0.1, 9999),
            'total' => $this->faker->randomFloat(2, 0.1, 9999),
        ];
    }
}
