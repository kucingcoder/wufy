<?php

namespace Database\Factories;

use App\Models\VisionMission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VisionMission>
 */
class VisionMissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vision' => 'Menjadi platform portfolio digital terbaik yang menginspirasi banyak orang.',
            'missions' => [
                'Memberikan layanan pengembangan web berkualitas tinggi.',
                'Membantu klien mewujudkan ide digital mereka.',
                'Terus berinovasi dalam teknologi terbaru.',
            ],
        ];
    }
}
