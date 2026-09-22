<?php

namespace App\Filament\Resources\Crms\Pages;

use App\Filament\Resources\Crms\CrmResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCrm extends EditRecord
{
    protected static string $resource = CrmResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
