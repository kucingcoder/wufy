<?php

namespace App\Filament\Resources\Projects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->circular(),
                TextColumn::make('title')
                    ->label('Judul Proyek')
                    ->searchable(),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->fontFamily('mono')
                    ->color('gray'),
                TextColumn::make('short_description')
                    ->label('Deskripsi Singkat')
                    ->limit(50),
                TextColumn::make('time')
                    ->label('Waktu')
                    ->state(fn ($record) => trim(($record->month ?? '') . ' ' . ($record->year ?? '')) ?: '-')
                    ->sortable(['year', 'month']),
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
