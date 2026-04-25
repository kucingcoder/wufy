<?php

namespace Database\Factories;

use App\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'job_title' => $this->faker->jobTitle(),
            'city' => $this->faker->city(),
            'province' => $this->faker->state(),
            'phone' => '08123456789',
            'email' => $this->faker->unique()->safeEmail(),
            'description' => $this->faker->paragraph(),
            'links' => [
                ['title' => 'GitHub', 'link' => 'https://github.com/example', 'logo' => 'https://cdn.simpleicons.org/github'],
                ['title' => 'LinkedIn', 'link' => 'https://linkedin.com/in/example', 'logo' => 'https://cdn.simpleicons.org/linkedin'],
            ],
            'avatar' => 'dummy/avatar.png',
            'cv_path' => null,
        ];
    }
}
