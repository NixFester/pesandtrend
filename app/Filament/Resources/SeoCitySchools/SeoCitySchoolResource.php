<?php

namespace App\Filament\Resources\SeoCitySchools;

use App\Filament\Resources\SeoCitySchools\Pages\CreateSeoCitySchool;
use App\Filament\Resources\SeoCitySchools\Pages\EditSeoCitySchool;
use App\Filament\Resources\SeoCitySchools\Pages\ListSeoCitySchools;
use App\Filament\Resources\SeoCitySchools\Schemas\SeoCitySchoolForm;
use App\Filament\Resources\SeoCitySchools\Tables\SeoCitySchoolsTable;
use App\Models\SeoCitySchool;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SeoCitySchoolResource extends Resource
{
    protected static ?string $model = SeoCitySchool::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static string|\UnitEnum|null $navigationGroup = 'SEO';

    protected static ?string $navigationLabel = 'Sekolah per Kota';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return SeoCitySchoolForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SeoCitySchoolsTable::configure($table);
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
            'index' => ListSeoCitySchools::route('/'),
            'create' => CreateSeoCitySchool::route('/create'),
            'edit' => EditSeoCitySchool::route('/{record}/edit'),
        ];
    }
}
