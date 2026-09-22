<?php

namespace App\Filament\Resources\Crms\Pages;

use App\Filament\Resources\Crms\CrmResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCrms extends ListRecords
{
    protected static string $resource = CrmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
