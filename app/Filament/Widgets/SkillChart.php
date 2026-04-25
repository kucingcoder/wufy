<?php

namespace App\Filament\Widgets;

use App\Models\Skill;
use Filament\Widgets\ChartWidget;

class SkillChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Kemampuan Berdasarkan Kategori';
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $data = Skill::selectRaw('category, count(*) as total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->toArray();

        // Map English keys to Indonesian Labels for chart
        $labels = [
            'bahasa' => 'Bahasa',
            'teknologi' => 'Teknologi',
            'minat' => 'Minat',
        ];

        $chartLabels = [];
        $chartData = [];

        foreach ($labels as $key => $label) {
            $chartLabels[] = $label;
            $chartData[] = $data[$key] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kemampuan',
                    'data' => $chartData,
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $chartLabels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
