<?php

namespace App\Filament\Resources\VisionMissions\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VisionMissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('vision')
                    ->label('Visi')
                    ->required()
                    ->rows(3),
                Repeater::make('missions')
                    ->label('Misi')
                    ->simple(
                        TextInput::make('mission')
                            ->required(),
                    )
                    ->maxItems(3)
                    ->required(),
            ]);
    }
}
