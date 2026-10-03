<?php

namespace App\Filament\Resources\Campaigns\Schemas;

use App\Models\Campaign;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CampaignForm
{
    public static function configure(Schema $schema): Schema
    {
        $components = [
            Section::make('Informasi Campaign')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul')
                        ->required(),
                    Forms\Components\Select::make('school_id')
                        ->label('Sekolah')
                        ->relationship('school', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\Select::make('category')
                        ->label('Kategori')
                        ->options(Campaign::categories())
                        ->required(),
                ]),

            Section::make('Deskripsi')
                ->schema([
                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(4)
                        ->required(),
                ]),

            Section::make('Target & Periode')
                ->schema([
                    Forms\Components\TextInput::make('target_amount')
                        ->label('Target Donasi')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),
                    Forms\Components\TextInput::make('current_amount')
                        ->label('Donasi Terkumpul')
                        ->numeric()
                        ->prefix('Rp'),
                    Forms\Components\DatePicker::make('start_date')
                        ->label('Tanggal Mulai')
                        ->required(),
                    Forms\Components\DatePicker::make('end_date')
                        ->label('Tanggal Berakhir'),
                ]),

            Section::make('Pengaturan')
                ->schema([
                    Forms\Components\Toggle::make('is_featured')
                        ->label('Tampilkan di Utama'),
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'draft' => 'Draft',
                            'active' => 'Aktif',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                        ])
                        ->default('draft'),
                ]),

            Section::make('Gambar')
                ->schema([
                    Forms\Components\FileUpload::make('image')
                        ->label('Gambar Campaign')
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios(['16:9', '4:3'])
                        ->disk('public')
                        ->visibility('public')
                        ->directory('campaigns')
                        ->maxSize(5120)
                        ->helperText('Maks 5MB. Format: JPG, PNG'),
                ]),
        ];

        // Add development helper notice
        if (app()->environment('local', 'development')) {
            array_unshift($components, Section::make('Development')
                ->description('In development mode - you can use "Generate 5 Random" button on the list page')
                ->icon('heroicon-o-sparkles'));
        }

        return $schema->columns(1)->components($components);
    }
}
