<?php

use Illuminate\Support\Facades\Route;

use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

use App\Models\Profile;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Service;
use App\Models\Certificate;

Route::get('/', function () {
    $profile = Profile::first();
    return Inertia::render('Welcome', [
        'profile' => $profile,
        'cv_exists' => $profile && $profile->cv_path && Storage::disk('public')->exists($profile->cv_path),
        'education' => Education::orderBy('sort')->get(),
        'experiences' => Experience::orderBy('sort')->get(),
        'projects' => Project::with('galleries')->orderBy('is_opensource', 'asc')->orderBy('year', 'desc')->latest()->get(),
        'skills' => Skill::orderBy('sort')->get(),
        'services' => Service::orderBy('sort')->get(),
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

Route::get('/terms', function () {
    $profile = Profile::first();
    return Inertia::render('Policy', [
        'title' => 'Syarat & Ketentuan',
        'content' => $profile ? $profile->terms_and_conditions : '',
        'author_name' => $profile ? $profile->full_name : '',
    ]);
});

Route::get('/privacy', function () {
    $profile = Profile::first();
    return Inertia::render('Policy', [
        'title' => 'Kebijakan Privasi',
        'content' => $profile ? $profile->privacy_policy : '',
        'author_name' => $profile ? $profile->full_name : '',
    ]);
});

Route::get('/refund', function () {
    $profile = Profile::first();
    return Inertia::render('Policy', [
        'title' => 'Kebijakan Pengembalian Dana',
        'content' => $profile ? $profile->refund_policy : '',
        'author_name' => $profile ? $profile->full_name : '',
    ]);
});

Route::get('/sitemap.xml', function () {
    $projects = Project::all();
    $profile = Profile::first();
    $now = now()->toAtomString();
    $url = config('app.url');

    $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
    $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

    // Home page
    $sitemap .= "<url><loc>{$url}/</loc><lastmod>{$now}</lastmod><changefreq>daily</changefreq><priority>1.0</priority></url>";

    // Sections (Deep links)
    $sections = [
        'services', 'projects', 'skills', 'experience', 'education', 'certificates', 'contact'
    ];
    foreach ($sections as $section) {
        $sitemap .= "<url><loc>{$url}/#{$section}</loc><lastmod>{$now}</lastmod><changefreq>monthly</changefreq><priority>0.8</priority></url>";
    }

    // Projects
    foreach ($projects as $project) {
        $lastmod = $project->updated_at ? $project->updated_at->toAtomString() : $now;
        $sitemap .= "<url><loc>{$url}/project/{$project->slug}</loc><lastmod>{$lastmod}</lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>";
    }

    // CV Link
    if ($profile && $profile->cv_path && Storage::disk('public')->exists($profile->cv_path)) {
        $sitemap .= "<url><loc>{$url}/storage/{$profile->cv_path}</loc><lastmod>{$now}</lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url>";
    }

    // Legal Pages
    $sitemap .= "<url><loc>{$url}/terms</loc><lastmod>{$now}</lastmod><changefreq>monthly</changefreq><priority>0.5</priority></url>";
    $sitemap .= "<url><loc>{$url}/privacy</loc><lastmod>{$now}</lastmod><changefreq>monthly</changefreq><priority>0.5</priority></url>";
    $sitemap .= "<url><loc>{$url}/refund</loc><lastmod>{$now}</lastmod><changefreq>monthly</changefreq><priority>0.5</priority></url>";

    $sitemap .= '</urlset>';

    return response($sitemap, 200)
        ->header('Content-Type', 'application/xml');
});

Route::get('/{code}', [\App\Http\Controllers\ShortLinkController::class, 'redirect'])->name('shortlink.redirect');
