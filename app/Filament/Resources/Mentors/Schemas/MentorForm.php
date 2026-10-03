<?php

namespace App\Filament\Resources\Mentors\Schemas;

use App\Models\MentorImage;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MentorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make('Informasi Mentor')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama')
                        ->required(),
                    Forms\Components\TextInput::make('tagline')
                        ->label('Tagline')
                        ->helperText('Contoh: "Mentor UTBK Fokus Matematika"'),
                    Forms\Components\TagsInput::make('expertise')
                        ->label('Keahlian')
                        ->placeholder('Tambah keahlian lalu tekan Enter')
                        ->helperText('Contoh: UTBK, Matematika, SMA'),
                    Forms\Components\TextInput::make('whatsapp_number')
                        ->label('No. WhatsApp')
                        ->tel()
                        ->required()
                        ->placeholder('081234567890'),
                ]),

            Section::make('Deskripsi')
                ->schema([
                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->rows(4)
                        ->required(),
                ]),

            Section::make('Harga & Pengaturan')
                ->schema([
                    Forms\Components\TextInput::make('price')
                        ->label('Harga')
                        ->numeric()
                        ->prefix('Rp')
                        ->required(),
                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                    Forms\Components\TextInput::make('sort_order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0),
                ]),

            Section::make('Galeri Gambar')
                ->description('Sertifikat, portofolio, dan contoh bimbel. Geser untuk mengatur urutan.')
                ->schema([
                    Forms\Components\Repeater::make('images')
                        ->relationship()
                        ->label('Gambar')
                        ->orderColumn('sort_order')
                        ->reorderable()
                        ->grid(2)
                        ->schema([
                            Forms\Components\FileUpload::make('image_path')
                                ->label('Gambar')
                                ->image()
                                ->imageEditor()
                                ->disk('public')
                                ->visibility('public')
                                ->directory(fn (?MentorImage $record) => 'mentors/'.($record?->mentor_id ?? 'new'))
                                ->maxSize(5120)
                                ->required()
                                ->columnSpan(1),
                            Forms\Components\TextInput::make('caption')
                                ->label('Caption')
                                ->columnSpan(1),
                            Forms\Components\Select::make('type')
                                ->label('Jenis')
                                ->options(MentorImage::types())
                                ->default('other')
                                ->required()
                                ->columnSpan(1),
                        ]),
                ]),
        ]);
    }
}
