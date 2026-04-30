<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use App\Models\Skill;
use App\Models\Certificate;
use App\Models\Experience;
use App\Models\Education;
use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Pengunjung Hari Ini', \App\Models\Visitor::whereDate('date', today())->count())
                ->description('Total pengunjung unik hari ini')
                ->descriptionIcon('heroicon-m-users')
                ->color('teal'),
            Stat::make('Total Proyek', Project::count())
                ->description('Proyek yang telah diselesaikan')
                ->descriptionIcon('heroicon-m-folder-open')
                ->color('success'),
            Stat::make('Total Kemampuan', Skill::count())
                ->description('Skill/Keahlian teknis')
                ->descriptionIcon('heroicon-m-light-bulb')
                ->color('warning'),
            Stat::make('Total Sertifikat', Certificate::count())
                ->description('Pencapaian & Lisensi')
                ->descriptionIcon('heroicon-m-ticket')
                ->color('info'),
            Stat::make('Pengalaman', Experience::count())
                ->description('Riwayat Karir/Organisasi')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('warning'),
            Stat::make('Pendidikan', Education::count())
                ->description('Riwayat Pendidikan')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color('gray'),
            Stat::make('Jasa/Layanan', Service::count())
                ->description('Layanan yang ditawarkan')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('danger'),
        ];
    }
}
