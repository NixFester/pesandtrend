<?php

namespace App\Filament\Resources;

use App\Models\HomepageSetting;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class HomepageSettingResource extends Resource
{
    protected static ?string $model = HomepageSetting::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Pengaturan Homepage';

    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?int $navigationSort = 90;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Statistik Hero')->schema([
                Forms\Components\TextInput::make('total_schools')->label('Total Sekolah')->numeric()->required(),
                Forms\Components\TextInput::make('total_students')->label('Total Santri')->numeric()->required(),
                Forms\Components\TextInput::make('total_cities')->label('Total Kota')->numeric()->required(),
                Forms\Components\TextInput::make('total_programs')->label('Total Program')->numeric()->required(),
            ])->columns(2),

            Section::make('Hero Banner')->schema([
                Forms\Components\TextInput::make('hero_title')->label('Judul Hero'),
                Forms\Components\Textarea::make('hero_subtitle')->label('Subjudul Hero')->rows(2),
                Forms\Components\TextInput::make('hero_image')->label('Gambar Hero')->placeholder('images/hero.jpg'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('total_schools')->label('Sekolah'),
                Tables\Columns\TextColumn::make('total_students')->label('Santri'),
                Tables\Columns\TextColumn::make('total_cities')->label('Kota'),
                Tables\Columns\TextColumn::make('total_programs')->label('Program'),
                Tables\Columns\TextColumn::make('updated_at')->label('Terakhir Diubah')->dateTime('d M Y H:i'),
            ])
            ->actions([Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\HomepageSettingResource\Pages\ListHomepageSettings::route('/'),
            'edit' => \App\Filament\Resources\HomepageSettingResource\Pages\EditHomepageSetting::route('/{record}/edit'),
        ];
    }
}
