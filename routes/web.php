<?php

use Illuminate\Support\Facades\Route;

use Inertia\Inertia;

use App\Models\Profile;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Service;
use App\Models\VisionMission;
use App\Models\Certificate;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'profile' => Profile::first(),
        'education' => Education::orderBy('sort')->get(),
        'experiences' => Experience::orderBy('sort')->get(),
        'projects' => Project::with('galleries')->latest()->get(),
        'skills' => Skill::orderBy('sort')->get(),
        'services' => Service::orderBy('sort')->get(),
        'visionMission' => VisionMission::first(),
        'certificates' => Certificate::orderBy('sort')->get(),
    ]);
});

Route::get('/project/{project}', function (Project $project) {
    return Inertia::render('ProjectDetail', [
        'project' => $project->load('galleries'),
    ]);
});

Route::get('/robots.txt', function () {
    $url = config('app.url');
    return response("User-agent: *\nAllow: /\n\nSitemap: {$url}/sitemap.xml", 200)
        ->header('Content-Type', 'text/plain');
});
