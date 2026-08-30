<?php

namespace App\Filament\Resources;

use App\Models\Article;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Artikel';

    protected static string|\UnitEnum|null $navigationGroup = 'Konten';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('title')->label('Judul')->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
            Forms\Components\TextInput::make('slug')->label('Slug')->required(),
            Forms\Components\TextInput::make('category')->label('Kategori')->default('Berita'),
            Forms\Components\Textarea::make('excerpt')->label('Kutipan Singkat')->rows(2),
            Forms\Components\RichEditor::make('content')->label('Isi Artikel')->required(),
            Forms\Components\Toggle::make('is_published')->label('Publikasikan')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable(),
                Tables\Columns\TextColumn::make('category')->label('Kategori'),
                Tables\Columns\IconColumn::make('is_published')->label('Publik')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y'),
            ])
            ->actions([Actions\EditAction::make()])
            ->bulkActions([Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\ArticleResource\Pages\ListArticles::route('/'),
            'create' => \App\Filament\Resources\ArticleResource\Pages\CreateArticle::route('/create'),
            'edit' => \App\Filament\Resources\ArticleResource\Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
