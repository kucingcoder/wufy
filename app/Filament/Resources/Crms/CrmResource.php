<?php

namespace App\Filament\Resources\Crms;

use App\Filament\Resources\Crms\Pages\CreateCrm;
use App\Filament\Resources\Crms\Pages\EditCrm;
use App\Filament\Resources\Crms\Pages\ListCrms;
use App\Filament\Resources\Crms\Schemas\CrmForm;
use App\Filament\Resources\Crms\Tables\CrmsTable;
use App\Models\Crm;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CrmResource extends Resource
{
    protected static ?string $model = Crm::class;

    protected static ?string $navigationLabel = 'CRM';
    protected static ?string $pluralLabel = 'CRM';
    protected static ?string $modelLabel = 'CRM';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?int $navigationSort = 8;

    protected static ?string $recordTitleAttribute = 'nama_projek';

    public static function form(Schema $schema): Schema
    {
        return CrmForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CrmsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCrms::route('/'),
            'create' => CreateCrm::route('/create'),
            'edit' => EditCrm::route('/{record}/edit'),
        ];
    }
}
