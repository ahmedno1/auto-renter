<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarFactory extends Factory
{
    protected $model = Car::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'image' => 'cars/' . fake()->uuid() . '.jpg',
            'brand' => fake()->company(),
            'model' => fake()->word(),
            'year' => fake()->numberBetween(2000, now()->year),
            'daily_rent' => fake()->randomFloat(2, 20, 500),
            'description' => fake()->sentence(),
            'status' => fake()->randomElement(['available', 'unavailable']),
        ];
    }
}