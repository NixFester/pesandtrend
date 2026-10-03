<?php

namespace App\Filament\Resources\SeoCitySchools\Schemas;

use App\Models\School;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SeoCitySchoolForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('seo_city_id')
                    ->label('Kota')
                    ->relationship('seoCity', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('school_id')
                    ->label('Sekolah')
                    ->relationship('school', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->options(function () {
                        return School::query()
                            ->published()
                            ->orderBy('name')
                            ->pluck('name', 'id');
                    }),
                TextInput::make('sort_order')
                    ->label('Urutan')
                    ->numeric()
                    ->default(0)
                    ->helperText('Nomor lebih kecil muncul lebih dulu'),
                Checkbox::make('is_featured')
                    ->label('Tampilkan sebagai Pilihan Utama')
                    ->default(false),
            ]);
    }
}
