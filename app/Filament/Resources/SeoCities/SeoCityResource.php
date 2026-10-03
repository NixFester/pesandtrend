<?php

namespace App\Filament\Resources\SeoCities;

use App\Filament\Resources\SeoCities\Pages\CreateSeoCity;
use App\Filament\Resources\SeoCities\Pages\EditSeoCity;
use App\Filament\Resources\SeoCities\Pages\ListSeoCities;
use App\Filament\Resources\SeoCities\Schemas\SeoCityForm;
use App\Filament\Resources\SeoCities\Tables\SeoCitiesTable;
use App\Models\SeoCity;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SeoCityResource extends Resource
{
    protected static ?string $model = SeoCity::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static string|\UnitEnum|null $navigationGroup = 'SEO';

    protected static ?string $navigationLabel = 'Kota SEO';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return SeoCityForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SeoCitiesTable::configure($table);
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
            'index' => ListSeoCities::route('/'),
            'create' => CreateSeoCity::route('/create'),
            'edit' => EditSeoCity::route('/{record}/edit'),
        ];
    }
}
