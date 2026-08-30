<?php

namespace App\Filament\Resources;

use App\Models\Testimonial;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
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
        return $schema->components([
            Forms\Components\TextInput::make('name')->label('Nama Pengulas')->required(),
            Forms\Components\TextInput::make('role')->label('Peran')->placeholder('Orang Tua Santri'),
            Forms\Components\Textarea::make('content')->label('Isi Ulasan')->required()->rows(3),
            Forms\Components\TextInput::make('rating')->label('Rating (1-5)')->numeric()->default(5),
            Forms\Components\Toggle::make('is_published')->label('Publikasikan')->default(true),
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
            'index' => \App\Filament\Resources\TestimonialResource\Pages\ListTestimonials::route('/'),
            'create' => \App\Filament\Resources\TestimonialResource\Pages\CreateTestimonial::route('/create'),
            'edit' => \App\Filament\Resources\TestimonialResource\Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
