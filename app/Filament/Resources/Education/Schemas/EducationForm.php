<?php

namespace App\Filament\Resources\Education\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class EducationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('institution')
                    ->label('Nama Institusi')
                    ->required()
                    ->maxLength(255),
                TextInput::make('major')
                    ->label('Jurusan')
                    ->required()
                    ->maxLength(255),
                TextInput::make('degree')
                    ->label('Gelar (jika ada)')
                    ->maxLength(255),
                TextInput::make('gpa')
                    ->label('IPK')
                    ->maxLength(255),
                TextInput::make('graduation_date')
                    ->label('Bulan & Tahun Lulus')
                    ->placeholder('Contoh: Juni 2024')
                    ->required()
                    ->maxLength(255),
                TextInput::make('location_text')
                    ->label('Lokasi (Teks)')
                    ->required()
                    ->maxLength(255),

            ]);
    }
}
