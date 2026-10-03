<?php

namespace App\Filament\Resources\Donations\Schemas;

use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DonationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            Section::make('Informasi Donatur')
                ->description('Data pribadi donatur')
                ->schema([
                    Forms\Components\TextInput::make('donor_name')
                        ->label('Nama Donatur')
                        ->disabled(),
                    Forms\Components\TextInput::make('donor_email')
                        ->label('Email')
                        ->disabled(),
                    Forms\Components\TextInput::make('donor_phone')
                        ->label('No. WhatsApp')
                        ->disabled(),
                    Forms\Components\Textarea::make('donor_message')
                        ->label('Pesan')
                        ->disabled(),
                ]),

            Section::make('Detail Donasi')
                ->description('Detail transaksi donasi')
                ->schema([
                    Forms\Components\Select::make('campaign_id')
                        ->label('Bantu Pesantren')
                        ->relationship('campaign', 'title')
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
                            'refunded' => 'Dikembalikan',
                        ]),
                    Forms\Components\TextInput::make('payment_method')
                        ->label('Metode Bayar')
                        ->disabled(),
                    Forms\Components\TextInput::make('xendit_id')
                        ->label('Xendit ID')
                        ->disabled(),
                ]),

            Section::make('Waktu')
                ->description('Timestamp donasi')
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
