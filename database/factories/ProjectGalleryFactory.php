<?php

namespace Database\Factories;

use App\Models\ProjectGallery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProjectGallery>
 */
class ProjectGalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => \App\Models\Project::factory(),
            'image_path' => 'dummy/project.png',
            'title' => $this->faker->sentence(3),
        ];
    }
}
