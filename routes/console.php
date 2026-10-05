<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('images:resize', function () {
    $this->info('Mencari gambar yang perlu di-resize...');
    
    $dir = storage_path('app/public');
    $maxWidth = 1024;
    $maxHeight = 1024;

    if (!is_dir($dir)) {
        $this->error('Direktori tidak ditemukan: ' . $dir);
        return;
    }

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    $processed = 0;

    foreach ($iterator as $file) {
        if ($file->isDir()) continue;
        
        $path = $file->getPathname();
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
            $info = @getimagesize($path);
            if (!$info) continue;
            
            $width = $info[0];
            $height = $info[1];
            
            if ($width > $maxWidth || $height > $maxHeight) {
                $this->comment("Resizing: " . basename($path) . " ({$width}x{$height})");
                
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
                    case IMAGETYPE_JPEG: $src = @imagecreatefromjpeg($path); break;
                    case IMAGETYPE_PNG: $src = @imagecreatefrompng($path); break;
                    case IMAGETYPE_GIF: $src = @imagecreatefromgif($path); break;
                    case IMAGETYPE_WEBP: $src = @imagecreatefromwebp($path); break;
                }
                
                if (!$src) continue;
                
                $dst = imagecreatetruecolor((int)$newWidth, (int)$newHeight);
                
                if (in_array($info[2], [IMAGETYPE_PNG, IMAGETYPE_WEBP])) {
                    imagealphablending($dst, false);
                    imagesavealpha($dst, true);
                    $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
                    imagefilledrectangle($dst, 0, 0, (int)$newWidth, (int)$newHeight, $transparent);
                }
                
                imagecopyresampled($dst, $src, 0, 0, 0, 0, (int)$newWidth, (int)$newHeight, $width, $height);
                
                switch ($info[2]) {
                    case IMAGETYPE_JPEG: imagejpeg($dst, $path, 85); break;
                    case IMAGETYPE_PNG: imagepng($dst, $path, 8); break;
                    case IMAGETYPE_GIF: imagegif($dst, $path); break;
                    case IMAGETYPE_WEBP: imagewebp($dst, $path, 85); break;
                }
                
                imagedestroy($src);
                imagedestroy($dst);
                $processed++;
            }
        }
    }

    $this->info("Selesai! $processed gambar berhasil di-resize.");
})->purpose('Resize gambar berukuran besar di storage/app/public agar SEO/LCP optimal');
