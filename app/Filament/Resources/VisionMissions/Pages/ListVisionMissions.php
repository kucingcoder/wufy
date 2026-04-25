<?php

namespace App\Filament\Resources\VisionMissions\Pages;

use App\Filament\Resources\VisionMissions\VisionMissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVisionMissions extends ListRecords
{
    protected static string $resource = VisionMissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
