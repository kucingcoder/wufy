<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Artisan;
use Filament\Notifications\Notification;

class SitemapWidget extends Widget
{
    protected static ?int $sort = 4;

    protected string $view = 'filament.widgets.sitemap-widget';

    protected int | string | array $columnSpan = 'full';

    public function generateSitemap()
    {
        try {
            Artisan::call('sitemap:generate');
            
            Notification::make()
                ->title('Sitemap Berhasil Dibuat')
                ->body('Sitemap telah berhasil diperbarui.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal Membuat Sitemap')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
