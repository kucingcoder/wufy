<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class MonthlyVisitorChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Grafik Pengunjung (Bulan Ini)';

    protected function getData(): array
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $daysInMonth = now()->daysInMonth;

        $visitors = \App\Models\Visitor::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->selectRaw('DATE(date) as date, count(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date');

        $chartData = [];
        $labels = [];
        
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dateString = $startOfMonth->copy()->addDays($i - 1)->toDateString();
            $chartData[] = $visitors->get($dateString, 0);
            $labels[] = (string) $i;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Pengunjung',
                    'data' => $chartData,
                    'fill' => 'start',
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
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
