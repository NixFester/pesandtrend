<?php

namespace App\Filament\Resources;

use App\Domain\Onboarding\ApplicationStatus;
use App\Filament\Resources\OnboardingApplicationResource\Pages\EditApplication;
use App\Filament\Resources\OnboardingApplicationResource\Pages\ListApplications;
use App\Models\Application;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class OnboardingApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Pendaftaran';

    protected static string|\UnitEnum|null $navigationGroup = 'Onboarding';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Sekolah & Siswa')->schema([
                Forms\Components\Select::make('school_id')->label('Sekolah')
                    ->relationship('school', 'name')->required()->searchable(),
                Forms\Components\TextInput::make('public_id')->label('Kode Pendaftaran')->disabled(),
                Forms\Components\TextInput::make('student_name')->label('Nama Siswa')->required(),
                Forms\Components\TextInput::make('student_nik')->label('NIK Siswa'),
                Forms\Components\Select::make('student_gender')->label('Jenis Kelamin')->options([
                    'Laki-laki' => 'Laki-laki',
                    'Perempuan' => 'Perempuan',
                ]),
                Forms\Components\TextInput::make('target_jenjang')->label('Jenjang Target'),
            ])->columns(2),

            Section::make('Informasi Orang Tua')->schema([
                Forms\Components\TextInput::make('parent_name')->label('Nama Orang Tua')->required(),
                Forms\Components\TextInput::make('parent_email')->label('Email')->email(),
                Forms\Components\TextInput::make('parent_phone')->label('No. Telepon'),
                Forms\Components\TextInput::make('parent_whatsapp')->label('No. WhatsApp'),
            ])->columns(2),

            Section::make('Status & Catatan')->schema([
                Forms\Components\Select::make('status')->label('Status Pendaftaran')
                    ->options(collect(ApplicationStatus::cases())->pluck('value', 'value')->toArray())
                    ->required(),
                Forms\Components\Textarea::make('rejection_reason')->label('Alasan Penolakan (jika ditolak)')->rows(2),
                Forms\Components\Textarea::make('notes')->label('Catatan')->rows(2),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('public_id')->label('Kode')->searchable(),
                Tables\Columns\TextColumn::make('student_name')->label('Nama Siswa')->searchable(),
                Tables\Columns\TextColumn::make('school.name')->label('Sekolah')->searchable(),
                Tables\Columns\TextColumn::make('parent_name')->label('Orang Tua'),
                Tables\Columns\TextColumn::make('status')->label('Status')
                    ->badge()
                    ->color(fn (ApplicationStatus $state): string => $state->color()),
                Tables\Columns\TextColumn::make('created_at')->label('Tanggal Daftar')->dateTime('d M Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(ApplicationStatus::cases())->pluck('value', 'value')->toArray()),
            ])
            ->actions([
                Actions\EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApplications::route('/'),
            'edit' => EditApplication::route('/{record}/edit'),
        ];
    }
}
