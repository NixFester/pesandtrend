<?php

namespace App\Filament\Resources\Donations\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DonationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d M Y'),
                TextColumn::make('donor_name')
                    ->label('Donatur')
                    ->searchable(),
                TextColumn::make('campaign.title')
                    ->label('Bantu Pesantren')
                    ->wrap(),
                TextColumn::make('amount')
                    ->label('Jumlah')
                    ->money('IDR'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
