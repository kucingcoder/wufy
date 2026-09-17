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

    protected int | string | array $columnSpan = 'full';

    public function getTableRecordKey($record): string
    {
        return (string) $record->source;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Visitor::query()
                    ->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year)
                    ->selectRaw("COALESCE(NULLIF(referer, ''), 'Direct') as source, count(*) as count")
                    ->groupBy('source')
                    ->orderByDesc('count')
            )
            ->columns([
                Tables\Columns\TextColumn::make('source')
                    ->label('Sumber'),
                Tables\Columns\TextColumn::make('count')
                    ->label('Jumlah Kunjungan')
                    ->badge()
                    ->color('primary'),
            ])
            ->headerActions([
                \Filament\Actions\Action::make('clearHistory')
                    ->label('Hapus History')
                    ->color('danger')
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus History Pengunjung')
                    ->modalDescription('Apakah Anda yakin ingin menghapus semua history pengunjung?')
                    ->action(function () {
                        Visitor::truncate();
                        \Filament\Notifications\Notification::make()
                            ->title('History pengunjung berhasil dihapus')
                            ->success()
                            ->send();
                    }),
            ])
            ->paginated(false);
    }
}
