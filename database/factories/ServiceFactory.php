<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->randomElement(['Web Development', 'UI/UX Design', 'Mobile Development', 'Backend API']),
            'price_range' => 'Rp ' . number_format($this->faker->numberBetween(1, 5), 0, ',', '.') . 'jt - ' . number_format($this->faker->numberBetween(6, 20), 0, ',', '.') . 'jt',
            'description' => $this->faker->sentence(10),
            'sort' => 0,
        ];
    }
}
