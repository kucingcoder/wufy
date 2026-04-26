<?php

namespace Database\Factories;

use App\Models\Certificate;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Certificate>
 */
class CertificateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'issuer' => $this->faker->company(),

            'category' => $this->faker->randomElement(['kursus', 'kompetensi']),
            'level' => $this->faker->randomElement(['dasar', 'menengah', 'tingkat lanjut', 'mahir', 'pakar']),
            'verification_url' => $this->faker->optional()->url(),
            'start_date' => $this->faker->monthName() . ' ' . ($this->faker->year() - 1),
            'end_date' => $this->faker->monthName() . ' ' . $this->faker->year(),
        ];
    }
}
