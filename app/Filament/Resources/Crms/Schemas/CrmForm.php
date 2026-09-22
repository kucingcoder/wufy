<?php

namespace App\Filament\Resources\Crms\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CrmForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_projek')
                    ->label('Nama Projek')
                    ->required(),
                TextInput::make('pic_nama')
                    ->label('Nama PIC')
                    ->required(),
                TextInput::make('pic_wa')
                    ->label('WhatsApp PIC')
                    ->required(),
                TextInput::make('link')
                    ->label('Link')
                    ->url()
                    ->default(null),
                TextInput::make('biaya_pengembangan')
                    ->label('Biaya Pengembangan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),
                Select::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->options(['DP' => 'DP', 'TERMIN' => 'TERMIN', 'LUNAS' => 'LUNAS'])
                    ->default('DP')
                    ->required(),
                TextInput::make('biaya_bulanan')
                    ->label('Biaya Bulanan')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0),
                DatePicker::make('terakhir_bayar')
                    ->label('Terakhir Bayar'),
            ]);
    }
}
