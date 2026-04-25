<?php

namespace App\Filament\Resources\Skills\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SkillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Nama Kemampuan')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('logo_path')
                    ->label('Logo / Ikon')
                    ->image()
                    ->formatStateUsing(fn ($state) => (array) $state)
                    ->disk('public')
                    ->visibility('public'),
                Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'bahasa' => 'Bahasa',
                        'teknologi' => 'Teknologi',
                        'minat' => 'Minat',
                    ])
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi Singkat')
                    ->maxLength(255),
            ]);
    }
}
