<?php

namespace App\Filament\Widgets;

use App\Models\Visitor;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class VisitorRefererWidget extends TableWidget
{
    protected static ?int $sort = 4;

    protected static ?string $heading = 'Sumber Pengunjung (Bulan Ini)';

    public function getTableRecordKey($record): string
    {
        return (string) $record->referer;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Visitor::query()
                    ->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year)
                    ->selectRaw('referer, count(*) as count')
                    ->groupBy('referer')
                    ->orderByDesc('count')
            )
            ->columns([
                Tables\Columns\TextColumn::make('referer')
                    ->label('Sumber')
                    ->placeholder('Direct'),
                Tables\Columns\TextColumn::make('count')
                    ->label('Jumlah Kunjungan')
                    ->badge()
                    ->color('primary'),
            ])
            ->paginated(false);
    }
}
