<?php

namespace App\Filament\Resources\ShortLinks;

use App\Filament\Resources\ShortLinks\Pages\CreateShortLink;
use App\Filament\Resources\ShortLinks\Pages\EditShortLink;
use App\Filament\Resources\ShortLinks\Pages\ListShortLinks;
use App\Filament\Resources\ShortLinks\Schemas\ShortLinkForm;
use App\Filament\Resources\ShortLinks\Tables\ShortLinksTable;
use App\Models\ShortLink;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ShortLinkResource extends Resource
{
    protected static ?string $model = ShortLink::class;

    protected static ?string $navigationLabel = 'Shortlink';
    protected static ?string $pluralLabel = 'Shortlink';
    protected static ?string $modelLabel = 'Shortlink';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;
    protected static ?int $navigationSort = 9;

    protected static ?string $recordTitleAttribute = 'code';

    public static function form(Schema $schema): Schema
    {
        return ShortLinkForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ShortLinksTable::configure($table);
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
            'index' => ListShortLinks::route('/'),
            'create' => CreateShortLink::route('/create'),
            'edit' => EditShortLink::route('/{record}/edit'),
        ];
    }
}
