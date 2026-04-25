<?php

namespace App\Filament\Resources\VisionMissions\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class VisionMissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vision')
                    ->label('Visi')
                    ->limit(50),
                TextColumn::make('missions_count')
                    ->label('Jumlah Misi')
                    ->state(fn ($record) => count($record->missions ?? []) . ' Poin'),
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
