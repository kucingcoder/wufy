<?php

namespace App\Filament\Resources\Experiences\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ExperienceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('company')
                    ->label('Nama Perusahaan / Organisasi')
                    ->required()
                    ->maxLength(255),
                TextInput::make('position')
                    ->label('Posisi / Jabatan')
                    ->required()
                    ->maxLength(255),
                Textarea::make('description')
                    ->label('Deskripsi Pekerjaan / Tanggung Jawab')
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make('location_text')
                    ->label('Lokasi (Teks)')
                    ->required()
                    ->maxLength(255),

                TextInput::make('start_date')
                    ->label('Mulai (Bulan/Tahun)')
                    ->placeholder('Contoh: Jan 2020')
                    ->required()
                    ->maxLength(255),
                TextInput::make('end_date')
                    ->label('Selesai (Bulan/Tahun)')
                    ->placeholder('Contoh: Sekarang atau Des 2023')
                    ->maxLength(255),
                Select::make('status')
                    ->label('Status Kerja')
                    ->options([
                        'Tetap' => 'Tetap',
                        'Kontrak' => 'Kontrak',
                        'Magang' => 'Magang',
                    ])
                    ->required(),
            ]);
    }
}
