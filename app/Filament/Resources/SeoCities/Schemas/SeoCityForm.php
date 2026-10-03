<?php

namespace App\Filament\Resources\SeoCities\Schemas;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SeoCityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Kota')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Kota')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: Yogyakarta'),
                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Contoh: yogyakarta')
                            ->unique(ignoreRecord: true)
                            ->helperText('Slug akan muncul di URL: /sekolah-terbaik/yogyakarta'),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                        Checkbox::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
                Section::make('Meta Tags (SEO)')
                    ->description('Pengaturan untuk optimasi mesin pencari')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(70)
                            ->placeholder('Judul yang muncul di Google (maksimal 60 karakter)')
                            ->helperText('Kosongkan untuk menggunakan judul default'),
                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->maxLength(160)
                            ->placeholder('Deskripsi yang muncul di Google (maksimal 160 karakter)')
                            ->helperText('Kosongkan untuk menggunakan deskripsi default'),
                    ]),
            ]);
    }
}
