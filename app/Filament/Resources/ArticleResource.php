<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages\CreateArticle;
use App\Filament\Resources\ArticleResource\Pages\EditArticle;
use App\Filament\Resources\ArticleResource\Pages\ListArticles;
use App\Models\Article;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationLabel = 'Artikel';

    protected static string|\UnitEnum|null $navigationGroup = 'Konten';

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make('Informasi Artikel')
                ->description('Judul, slug, dan kategori artikel')
                ->icon('heroicon-m-document-text')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Judul')
                        ->placeholder('Contoh: 5 Tips Memilih Sekolah Islam')
                        ->required(),
                    Forms\Components\Hidden::make('slug'),
                    Forms\Components\TextInput::make('category')
                        ->label('Kategori')
                        ->placeholder('Contoh: Panduan')
                        ->default('Berita'),
                ]),

            Section::make('Konten')
                ->description('Kutipan singkat dan isi artikel')
                ->icon('heroicon-m-book-open')
                ->schema([
                    Forms\Components\Textarea::make('excerpt')
                        ->label('Kutipan Singkat')
                        ->placeholder('Deskripsi singkat untuk preview artikel...')
                        ->rows(3),
                    Forms\Components\RichEditor::make('content')
                        ->label('Isi Artikel')
                        ->placeholder('Tulis konten artikel di sini...')
                        ->toolbarButtons([
                            'attachFiles',
                            'blockquote',
                            'bold',
                            'bulletList',
                            'codeBlock',
                            'h2',
                            'h3',
                            'italic',
                            'link',
                            'orderedList',
                            'redo',
                            'strike',
                            'underline',
                            'undo',
                        ])
                        ->fileAttachmentsDirectory('articles/attachments'),
                ]),

            Section::make('Pengaturan')
                ->description('Pengaturan publikasi')
                ->icon('heroicon-m-cog')
                ->schema([
                    Forms\Components\Toggle::make('is_published')
                        ->label('Publikasikan')
                        ->default(true)
                        ->helperText('Jika aktif, artikel akan muncul di halaman publik'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Judul')->searchable(),
                Tables\Columns\TextColumn::make('category')->label('Kategori'),
                Tables\Columns\IconColumn::make('published_at')
                    ->label('Publik')
                    ->boolean()
                    ->getStateUsing(fn ($record) => ! is_null($record->published_at)),
                Tables\Columns\TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y'),
            ])
            ->actions([Actions\EditAction::make()])
            ->bulkActions([Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArticles::route('/'),
            'create' => CreateArticle::route('/create'),
            'edit' => EditArticle::route('/{record}/edit'),
        ];
    }
}
