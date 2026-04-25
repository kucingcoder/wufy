<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Skill>
 */
class SkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'logo_path' => 'dummy/skill.png',
            'category' => $this->faker->randomElement(['bahasa', 'teknologi', 'minat']),
            'description' => $this->faker->sentence(),
        ];
    }
}
