<?php

namespace App\Filament\Resources\VisionMissions\Pages;

use App\Filament\Resources\VisionMissions\VisionMissionResource;
use App\Models\VisionMission;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListVisionMissions extends ListRecords
{
    protected static string $resource = VisionMissionResource::class;

    public function mount(): void
    {
        $record = VisionMission::first();

        if ($record) {
            $this->redirect(VisionMissionResource::getUrl('edit', ['record' => $record]));

            return;
        }

        $this->redirect(VisionMissionResource::getUrl('create'));
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
