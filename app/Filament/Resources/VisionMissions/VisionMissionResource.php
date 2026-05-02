<?php

namespace App\Filament\Resources\VisionMissions;

use App\Filament\Resources\VisionMissions\Pages\CreateVisionMission;
use App\Filament\Resources\VisionMissions\Pages\EditVisionMission;
use App\Filament\Resources\VisionMissions\Pages\ListVisionMissions;
use App\Filament\Resources\VisionMissions\Schemas\VisionMissionForm;
use App\Filament\Resources\VisionMissions\Tables\VisionMissionsTable;
use App\Models\VisionMission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class VisionMissionResource extends Resource
{
    protected static ?string $model = VisionMission::class;

    protected static ?string $navigationLabel = 'Visi Misi';
    protected static ?string $pluralLabel = 'Visi Misi';
    protected static ?string $modelLabel = 'Visi Misi';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-eye';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return VisionMissionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VisionMissionsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVisionMissions::route('/'),
            'create' => CreateVisionMission::route('/create'),
            'edit' => EditVisionMission::route('/{record}/edit'),
        ];
    }
}
