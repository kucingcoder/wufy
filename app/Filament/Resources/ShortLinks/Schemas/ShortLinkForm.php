<?php

namespace App\Filament\Resources\ShortLinks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ShortLinkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Short Code')
                    ->placeholder('e.g. promo-2024')
                    ->unique(ignoreRecord: true)
                    ->prefix(config('app.url') . '/')
                    ->helperText('Biarkan kosong untuk generate otomatis')
                    ->dehydrateStateUsing(fn ($state) => $state ?: Str::random(6)),
                
                TextInput::make('original_url')
                    ->label('Original URL')
                    ->url()
                    ->required()
                    ->placeholder('https://example.com/very-long-url-path'),
                
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }
}
