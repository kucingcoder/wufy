<?php

namespace App\Filament\Resources\Crms\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CrmsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_projek')
                    ->label('Nama Projek')
                    ->searchable(),
                TextColumn::make('pic_nama')
                    ->label('PIC')
                    ->description(fn ($record) => $record->pic_wa)
                    ->url(fn ($record) => 'https://wa.me/' . preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $record->pic_wa)))
                    ->openUrlInNewTab()
                    ->searchable(['pic_nama', 'pic_wa']),
                TextColumn::make('link')
                    ->label('Link')
                    ->searchable(),
                TextColumn::make('biaya_pengembangan')
                    ->label('Pengembangan')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('status_pembayaran')
                    ->label('Pembayaran')
                    ->badge(),
                TextColumn::make('biaya_bulanan')
                    ->label('Bulanan')
                    ->money('IDR')
                    ->sortable(),
                TextColumn::make('terakhir_bayar')
                    ->label('Terakhir Bayar')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
