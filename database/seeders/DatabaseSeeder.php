<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::factory()->create([
            'name' => 'Admin Wufy',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ]);

        // Profile (only 1)
        \App\Models\Profile::factory()->create([
            'full_name' => 'Admin Wufy',
            'email' => 'admin@wufy.test',
            'cv_path' => 'dummy/cv.pdf',
        ]);

        // Other data
        \App\Models\Education::factory(2)->create();
        \App\Models\Experience::factory(3)->create();
        \App\Models\Certificate::factory(5)->create();
        \App\Models\Skill::factory(10)->create();
        
        \App\Models\Project::factory(18)->create()->each(function ($project) {
            \App\Models\ProjectGallery::factory(3)->create([
                'project_id' => $project->id,
            ]);
        });

        // Services & Vision Mission
        \App\Models\Service::factory(4)->create();
        \App\Models\VisionMission::factory()->create();
    }
}
