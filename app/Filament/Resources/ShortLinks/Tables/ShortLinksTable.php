<?php

namespace App\Filament\Resources\ShortLinks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShortLinksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('short_url')
                    ->label('Short URL')
                    ->searchable(['code'])
                    ->sortable(['code'])
                    ->copyable()
                    ->copyMessage('Short link copied'),
                
                TextColumn::make('original_url')
                    ->label('Original URL')
                    ->searchable()
                    ->limit(50),
                
                TextColumn::make('clicks')
                    ->label('Clicks')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                
                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean(),
                
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
