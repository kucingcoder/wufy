<?php

namespace App\Filament\Resources\Services\Schemas;


use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul Jasa')
                    ->required()
                    ->maxLength(255),

                TextInput::make('price_range')
                    ->label('Range Harga')
                    ->placeholder('contoh: Rp 1jt - 5jt')
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->rows(5),
            ]);
    }
}
