<?php

namespace App\Filament\Resources\Certificates\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class CertificateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul Sertifikat')
                    ->required()
                    ->maxLength(255),
                TextInput::make('issuer')
                    ->label('Penerbit / Penyelenggara')
                    ->required()
                    ->maxLength(255),

                Select::make('category')
                    ->label('Kategori')
                    ->options([
                        'kursus' => 'Kursus',
                        'kompetensi' => 'Kompetensi',
                    ])
                    ->required(),
                Select::make('level')
                    ->label('Tingkat / Level')
                    ->options([
                        'pemula' => 'Pemula',
                        'menengah' => 'Menengah',
                        'tingkat lanjut' => 'Tingkat Lanjut',
                        'mahir' => 'Mahir',
                        'pakar' => 'Pakar',
                    ])
                    ->required(),
                TextInput::make('start_date')
                    ->label('Mulai Berlaku')
                    ->placeholder('Bulan/Tahun')
                    ->required()
                    ->maxLength(255),
                TextInput::make('end_date')
                    ->label('Sampai Dengan')
                    ->placeholder('Bulan/Tahun atau Seumur Hidup')
                    ->maxLength(255),
            ]);
    }
}
