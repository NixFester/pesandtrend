<?php

namespace App\Filament\Resources\Mentors\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MentorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('tagline')
                    ->label('Tagline')
                    ->limit(40)
                    ->wrap(),
                TextColumn::make('expertise')
                    ->label('Keahlian')
                    ->badge()
                    ->separator(', '),
                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR'),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (bool $state) => $state ? 'Aktif' : 'Nonaktif')
                    ->color(fn (bool $state) => $state ? 'success' : 'gray'),
                TextColumn::make('sort_order')
                    ->label('Urutan'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->defaultSort('sort_order');
    }
}
