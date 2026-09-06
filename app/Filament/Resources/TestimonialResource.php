<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages\CreateTestimonial;
use App\Filament\Resources\TestimonialResource\Pages\EditTestimonial;
use App\Filament\Resources\TestimonialResource\Pages\ListTestimonials;
use App\Models\Testimonial;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Testimoni';

    protected static string|\UnitEnum|null $navigationGroup = 'Konten';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make('Informasi Testimoni')
                ->description('Nama dan peran pengulas')
                ->icon('heroicon-m-user')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Nama Pengulas')
                        ->placeholder('Contoh: Ibu Sari Rahayu')
                        ->required(),
                    Forms\Components\Hidden::make('slug'),
                    Forms\Components\TextInput::make('role')
                        ->label('Peran')
                        ->placeholder('Contoh: Orang Tua Santri'),
                ]),

            Section::make('Konten Testimoni')
                ->description('Isi ulasan atau testimoni')
                ->icon('heroicon-m-chat-bubble-left-right')
                ->schema([
                    Forms\Components\Textarea::make('quote')
                        ->label('Isi Testimoni')
                        ->placeholder('Tulis testimoni atau ulasan di sini...')
                        ->required()
                        ->rows(4),
                    Forms\Components\TextInput::make('rating')
                        ->label('Rating (1-5)')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(5)
                        ->default(5),
                ]),

            Section::make('Pengaturan')
                ->description('Pengaturan publikasi')
                ->icon('heroicon-m-cog')
                ->schema([
                    Forms\Components\Toggle::make('is_published')
                        ->label('Publikasikan')
                        ->default(true)
                        ->helperText('Jika aktif, testimoni akan muncul di halaman publik'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Nama')->searchable(),
                Tables\Columns\TextColumn::make('role')->label('Peran'),
                Tables\Columns\TextColumn::make('rating')->label('Rating'),
                Tables\Columns\IconColumn::make('is_published')->label('Publik')->boolean(),
            ])
            ->actions([Actions\EditAction::make()])
            ->bulkActions([Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTestimonials::route('/'),
            'create' => CreateTestimonial::route('/create'),
            'edit' => EditTestimonial::route('/{record}/edit'),
        ];
    }
}
