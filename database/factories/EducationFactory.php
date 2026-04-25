<?php

namespace Database\Factories;

use App\Models\Education;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Education>
 */
class EducationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'institution' => $this->faker->company() . ' University',
            'major' => $this->faker->jobTitle(),
            'degree' => $this->faker->randomElement(['Bachelor', 'Master', 'PhD']),
            'graduation_date' => $this->faker->monthName() . ' ' . $this->faker->year(),
            'location_text' => $this->faker->city(),
            'gpa' => $this->faker->randomFloat(2, 3, 4),

        ];
    }
}
