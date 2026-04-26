<?php

namespace App\Filament\Widgets;

use App\Models\Project;
use Filament\Widgets\ChartWidget;

class ProjectChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Pertumbuhan Proyek (Tahun Ini)';

    protected function getData(): array
    {
        $projects = Project::where('year', date('Y'))->get();
        
        $monthMap = [
            'Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4,
            'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8,
            'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12,
        ];

        $chartData = array_fill(0, 12, 0);

        foreach ($projects as $project) {
            $monthNum = $monthMap[$project->month] ?? null;
            if ($monthNum) {
                $chartData[$monthNum - 1]++;
            }
        }

        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        return [
            'datasets' => [
                [
                    'label' => 'Proyek',
                    'data' => $chartData,
                    'fill' => 'start',
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
