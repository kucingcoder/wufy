<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class VisitorChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Grafik Pengunjung (Minggu Ini)';

    protected function getData(): array
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $visitors = \App\Models\Visitor::whereBetween('date', [$startOfWeek, $endOfWeek])
            ->selectRaw('DATE(date) as date, count(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartData = [];
        $labels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
        
        $currentDate = $startOfWeek->copy();
        
        for ($i = 0; $i < 7; $i++) {
            $dateString = $currentDate->toDateString();
            $chartData[] = $visitors->get($dateString, 0);
            $currentDate->addDay();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pengunjung',
                    'data' => $chartData,
                    'fill' => 'start',
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
