<?php

namespace App\Filament\Resources\MentorBookings\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MentorBookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            Section::make('Informasi Klien')
                ->description('Data klien yang memesan bimbel')
                ->schema([
                    Forms\Components\TextInput::make('client_name')
                        ->label('Nama Klien')
                        ->disabled(),
                    Forms\Components\TextInput::make('client_email')
                        ->label('Email')
                        ->disabled(),
                    Forms\Components\TextInput::make('client_whatsapp')
                        ->label('No. WhatsApp')
                        ->disabled(),
                ]),

            Section::make('Detail Booking')
                ->description('Detail transaksi booking bimbel')
                ->schema([
                    Forms\Components\TextInput::make('code')
                        ->label('Kode Booking')
                        ->disabled(),
                    Forms\Components\Select::make('mentor_id')
                        ->label('Mentor')
                        ->relationship('mentor', 'name')
                        ->disabled(),
                    Forms\Components\TextInput::make('amount')
                        ->label('Jumlah')
                        ->disabled()
                        ->prefix('Rp'),
                    Forms\Components\Select::make('status')
                        ->label('Status')
                        ->options([
                            'pending' => 'Menunggu',
                            'paid' => 'Berhasil',
                            'failed' => 'Gagal',
                            'expired' => 'Kedaluwarsa',
                            'cancelled' => 'Dibatalkan',
                        ]),
                    Forms\Components\TextInput::make('payment_method')
                        ->label('Metode Bayar')
                        ->disabled(),
                    Forms\Components\TextInput::make('xendit_id')
                        ->label('Xendit ID')
                        ->disabled(),
                    Forms\Components\TextInput::make('invoice_url')
                        ->label('URL Invoice')
                        ->disabled(),
                ]),

            Section::make('Waktu')
                ->description('Timestamp booking')
                ->schema([
                    Forms\Components\TextInput::make('created_at')
                        ->label('Dibuat')
                        ->disabled(),
                    Forms\Components\TextInput::make('paid_at')
                        ->label('Waktu Bayar')
                        ->disabled(),
                ]),
        ]);
    }
}
