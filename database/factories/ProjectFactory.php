<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $title = $this->faker->sentence(4),
            'slug' => \Illuminate\Support\Str::slug($title),
            'month' => $this->faker->randomElement([
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ]),
            'year' => $this->faker->year(),
            'thumbnail' => 'dummy/project.png',
            'short_description' => $this->faker->paragraph(1),
            'blog_content' => $this->faker->paragraphs(3, true),
        ];
    }
}
