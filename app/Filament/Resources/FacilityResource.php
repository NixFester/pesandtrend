<?php

namespace App\Filament\Resources;

use App\Models\Facility;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Fasilitas';

    protected static string|\UnitEnum|null $navigationGroup = 'Katalog';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')->label('Nama Fasilitas')->required(),
            Forms\Components\TextInput::make('icon')->label('Ikon')->placeholder('mosque'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('icon')->label('Ikon'),
                Tables\Columns\TextColumn::make('schools_count')->label('Sekolah')->counts('schools'),
            ])
            ->actions([Actions\EditAction::make()])
            ->bulkActions([Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\FacilityResource\Pages\ListFacilities::route('/'),
            'create' => \App\Filament\Resources\FacilityResource\Pages\CreateFacility::route('/create'),
            'edit' => \App\Filament\Resources\FacilityResource\Pages\EditFacility::route('/{record}/edit'),
        ];
    }
}
