<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('images:resize', function () {
    $this->info('Mencari gambar dari database untuk di-resize...');
    
    $processed = 0;

    $resizeImage = function ($path, $maxWidth, $maxHeight) use (&$processed) {
        $fullPath = storage_path('app/public/' . $path);
        if (!file_exists($fullPath) || !is_file($fullPath)) return;
        
        $info = @getimagesize($fullPath);
        if (!$info) return;
        
        $width = $info[0];
        $height = $info[1];
        
        if ($width > $maxWidth || $height > $maxHeight) {
            $this->comment("Resizing: " . basename($fullPath) . " ({$width}x{$height} -> maks {$maxWidth}x{$maxHeight})");
            
            // Pertahankan rasio asli (as requested by user)
            $ratio = $width / $height;
            if ($maxWidth / $maxHeight > $ratio) {
                $newWidth = $maxHeight * $ratio;
                $newHeight = $maxHeight;
            } else {
                $newHeight = $maxWidth / $ratio;
                $newWidth = $maxWidth;
            }
            
            $src = null;
            switch ($info[2]) {
                case IMAGETYPE_JPEG: $src = @imagecreatefromjpeg($fullPath); break;
                case IMAGETYPE_PNG: $src = @imagecreatefrompng($fullPath); break;
                case IMAGETYPE_GIF: $src = @imagecreatefromgif($fullPath); break;
                case IMAGETYPE_WEBP: $src = @imagecreatefromwebp($fullPath); break;
            }
            
            if (!$src) return;
            
            $dst = imagecreatetruecolor((int)$newWidth, (int)$newHeight);
            
            if (in_array($info[2], [IMAGETYPE_PNG, IMAGETYPE_WEBP])) {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
                imagefilledrectangle($dst, 0, 0, (int)$newWidth, (int)$newHeight, $transparent);
            }
            
            imagecopyresampled($dst, $src, 0, 0, 0, 0, (int)$newWidth, (int)$newHeight, $width, $height);
            
            switch ($info[2]) {
                case IMAGETYPE_JPEG: imagejpeg($dst, $fullPath, 85); break;
                case IMAGETYPE_PNG: imagepng($dst, $fullPath, 8); break;
                case IMAGETYPE_GIF: imagegif($dst, $fullPath); break;
                case IMAGETYPE_WEBP: imagewebp($dst, $fullPath, 85); break;
            }
            
            imagedestroy($src);
            imagedestroy($dst);
            $processed++;
        }
    };

    // 1. Profile Avatar (Maks 512x512)
    $profile = \App\Models\Profile::first();
    if ($profile && $profile->avatar) {
        $resizeImage($profile->avatar, 512, 512);
    }

    // 2. Project Thumbnails (Maks 1024x1024)
    $projects = \App\Models\Project::all();
    foreach ($projects as $project) {
        if ($project->thumbnail) {
            $resizeImage($project->thumbnail, 1024, 1024);
        }
    }

    // 3. Project Galleries (Maks 1024x1024)
    if (class_exists(\App\Models\ProjectGallery::class)) {
        $galleries = \App\Models\ProjectGallery::all();
        foreach ($galleries as $gallery) {
            if ($gallery->image_path) {
                $resizeImage($gallery->image_path, 1024, 1024);
            }
        }
    }

    // 4. Skills (Maks 256x256)
    $skills = \App\Models\Skill::all();
    foreach ($skills as $skill) {
        if ($skill->logo_path) {
            $resizeImage($skill->logo_path, 256, 256);
        }
    }

    $this->info("Selesai! $processed gambar tambahan berhasil di-resize berdasarkan model.");
})->purpose('Resize gambar di database sesuai peruntukannya (SEO/LCP optimal)');
