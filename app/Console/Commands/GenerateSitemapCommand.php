<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\Profile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap for the website.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/')
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY));

        // Menambahkan menu utama sebagai deep link (untuk mengejar Google Sitelinks)
        $sections = [
            ['url' => '/#vision', 'priority' => 0.9, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => '/#services', 'priority' => 0.9, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => '/#projects', 'priority' => 0.9, 'freq' => Url::CHANGE_FREQUENCY_WEEKLY],
            ['url' => '/#skills', 'priority' => 0.8, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => '/#experience', 'priority' => 0.8, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => '/#education', 'priority' => 0.8, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => '/#certificates', 'priority' => 0.8, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
            ['url' => '/#contact', 'priority' => 0.7, 'freq' => Url::CHANGE_FREQUENCY_MONTHLY],
        ];

        foreach ($sections as $section) {
            $sitemap->add(Url::create($section['url'])
                ->setPriority($section['priority'])
                ->setChangeFrequency($section['freq']));
        }

        Project::all()->each(function (Project $project) use ($sitemap) {
            $sitemap->add(Url::create("/project/{$project->slug}")
                ->setPriority(0.8)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY));
        });

        // Add CV Link if exists
        $profile = Profile::first();
        if ($profile && $profile->cv_path && Storage::disk('public')->exists($profile->cv_path)) {
            $sitemap->add(Url::create("/storage/{$profile->cv_path}")
                ->setPriority(0.7)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY));
        }

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated successfully!');
    }
}
