<?php

namespace App\Filament\Resources\MentorBookings\Tables;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MentorBookingsTable
{
    public static function configure(Table $table): Table
    {
        $toolbarActions = [
            Action::make('exportCsv')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(function (Table $table) {
                    $records = $table->getFilteredTableQuery()->get();

                    $filename = 'booking-bimbel-'.now()->format('Ymd-His').'.csv';

                    return response()->streamDownload(function () use ($records) {
                        $handle = fopen('php://output', 'w');

                        fputcsv($handle, [
                            'Kode', 'Tanggal', 'Mentor', 'Nama Klien', 'Email', 'WhatsApp',
                            'Jumlah', 'Status', 'Xendit ID', 'Metode Bayar', 'Dibayar Pada',
                        ]);

                        foreach ($records as $record) {
                            fputcsv($handle, [
                                $record->code,
                                $record->created_at->format('d/m/Y H:i'),
                                $record->mentor->name,
                                $record->client_name,
                                $record->client_email,
                                $record->client_whatsapp,
                                $record->amount,
                                $record->status,
                                $record->xendit_id,
                                $record->payment_method,
                                $record->paid_at?->format('d/m/Y H:i'),
                            ]);
                        }

                        fclose($handle);
                    }, $filename, ['Content-Type' => 'text/csv']);
                }),
        ];

        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->dateTime('H:i'),
                TextColumn::make('code')
                    ->label('Kode')
                    ->fontFamily('mono'),
                TextColumn::make('mentor.name')
                    ->label('Mentor')
                    ->wrap(),
                TextColumn::make('client_name')
                    ->label('Klien')
                    ->searchable(),
                TextColumn::make('client_whatsapp')
                    ->label('WhatsApp'),
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
                        'expired' => 'gray',
                        'cancelled' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('invoice_url')
                    ->label('Invoice')
                    ->url(fn (?string $state): ?string => $state)
                    ->limit(20)
                    ->openUrlInNewTab()
                    ->placeholder('-'),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ])
            ->toolbarActions($toolbarActions)
            ->defaultSort('created_at', 'desc');
    }
}
