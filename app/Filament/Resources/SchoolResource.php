<?php

namespace App\Filament\Resources;

use App\Models\School;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class SchoolResource extends Resource
{
    protected static ?string $model = School::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Sekolah';

    protected static string|\UnitEnum|null $navigationGroup = 'Katalog';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Utama')->schema([
                Forms\Components\TextInput::make('name')->label('Nama Sekolah')->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                Forms\Components\TextInput::make('slug')->label('Slug')->required(),
                Forms\Components\Select::make('type')->label('Tipe Sekolah')->options([
                    'Pesantren Modern' => 'Pesantren Modern',
                    'Pesantren Salaf' => 'Pesantren Salaf',
                    'SDIT' => 'SDIT',
                    'SMPIT' => 'SMPIT',
                    'SMAIT' => 'SMAIT',
                ])->required(),
                Forms\Components\TextInput::make('city')->label('Kota')->required(),
                Forms\Components\TextInput::make('province')->label('Provinsi')->required(),
                Forms\Components\Textarea::make('address')->label('Alamat Lengkap')->rows(2),
            ])->columns(2),

            Section::make('Kontak & Geolokasi')->schema([
                Forms\Components\TextInput::make('whatsapp_e164')->label('No. WhatsApp (e.g. +6281234567890)'),
                Forms\Components\TextInput::make('latitude')->label('Latitude')->numeric(),
                Forms\Components\TextInput::make('longitude')->label('Longitude')->numeric(),
            ])->columns(3),

            Section::make('Rincian Biaya (IDR)')->schema([
                Forms\Components\TextInput::make('uang_pangkal')->label('Uang Pangkal')->numeric()->default(0),
                Forms\Components\TextInput::make('spp_monthly')->label('SPP Bulanan')->numeric()->default(0),
                Forms\Components\TextInput::make('asrama_monthly')->label('Biaya Asrama / Bln')->numeric()->default(0),
                Forms\Components\TextInput::make('seragam_fee')->label('Biaya Seragam')->numeric()->default(0),
                Forms\Components\TextInput::make('ekskul_fee')->label('Biaya Ekskul / Thn')->numeric()->default(0),
                Forms\Components\TextInput::make('study_tour_fee')->label('Biaya Study Tour / Thn')->numeric()->default(0),
            ])->columns(3),

            Section::make('Status Publishing')->schema([
                Forms\Components\Toggle::make('is_published')->label('Dipublikasikan')->default(true),
                Forms\Components\Toggle::make('is_verified')->label('Terverifikasi')->default(false),
                Forms\Components\Toggle::make('is_boarding')->label('Berasrama')->default(true),
                Forms\Components\Toggle::make('registration_open')->label('Pendaftaran Dibuka')->default(true),
            ])->columns(4),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama Sekolah')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('city')->label('Kota')->searchable(),
                Tables\Columns\TextColumn::make('type')->label('Tipe'),
                Tables\Columns\TextColumn::make('spp_monthly')->label('SPP / Bln')->money('IDR')->sortable(),
                Tables\Columns\IconColumn::make('is_published')->label('Publik')->boolean(),
                Tables\Columns\IconColumn::make('is_verified')->label('Verifikasi')->boolean(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')->label('Publik'),
                Tables\Filters\TernaryFilter::make('is_verified')->label('Terverifikasi'),
            ])
            ->actions([
                Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\SchoolResource\Pages\ListSchools::route('/'),
            'create' => \App\Filament\Resources\SchoolResource\Pages\CreateSchool::route('/create'),
            'edit' => \App\Filament\Resources\SchoolResource\Pages\EditSchool::route('/{record}/edit'),
        ];
    }
}
