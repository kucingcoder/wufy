<?php

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company' => $this->faker->company(),
            'position' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraph(2),
            'location_text' => $this->faker->city(),

            'start_date' => $this->faker->monthName() . ' ' . ($this->faker->year() - 2),
            'end_date' => $this->faker->randomElement([$this->faker->monthName() . ' ' . $this->faker->year(), 'Present']),
            'status' => $this->faker->randomElement(['Tetap', 'Kontrak', 'Magang']),
        ];
    }
}
