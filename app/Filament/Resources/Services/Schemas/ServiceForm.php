<?php

namespace App\Filament\Resources\Services\Schemas;

use Guava\IconPicker\Forms\Components\IconPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
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
                IconPicker::make('icon')
                    ->label('Pilih Icon')
                    ->columns([
                        'default' => 1,
                        'lg' => 3,
                        '2xl' => 5,
                    ])
                    ->required(),
                TextInput::make('price_range')
                    ->label('Range Harga')
                    ->placeholder('contoh: Rp 1jt - 5jt')
                    ->required(),
                RichEditor::make('description')
                    ->label('Deskripsi')
                    ->required(),
            ]);
    }
}
