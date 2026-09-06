<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationPaymentResource\Pages\CreateApplicationPayment;
use App\Filament\Resources\ApplicationPaymentResource\Pages\EditApplicationPayment;
use App\Filament\Resources\ApplicationPaymentResource\Pages\ListApplicationPayments;
use App\Models\ApplicationPayment;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ApplicationPaymentResource extends Resource
{
    protected static ?string $model = ApplicationPayment::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Pembayaran';

    protected static string|\UnitEnum|null $navigationGroup = 'Onboarding';

    protected static ?int $navigationSort = 11;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Select::make('application_id')->label('Pendaftaran')
                ->relationship('application', 'public_id')->required()->searchable(),
            Forms\Components\Select::make('provider')->label('Provider')->options([
                'xendit' => 'Xendit',
                'manual' => 'Manual',
            ])->default('manual'),
            Forms\Components\TextInput::make('amount')->label('Jumlah (IDR)')->numeric()->required(),
            Forms\Components\Select::make('status')->label('Status')->options([
                'pending' => 'Pending',
                'paid' => 'Terbayar',
                'expired' => 'Kadaluarsa',
                'failed' => 'Gagal',
            ])->default('pending'),
            Forms\Components\TextInput::make('payment_method')->label('Metode Pembayaran'),
            Forms\Components\DateTimePicker::make('paid_at')->label('Tanggal Bayar'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('application.public_id')->label('Kode Daftar')->searchable(),
                Tables\Columns\TextColumn::make('application.student_name')->label('Siswa'),
                Tables\Columns\TextColumn::make('provider')->label('Provider')->badge(),
                Tables\Columns\TextColumn::make('amount')->label('Jumlah')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'expired' => 'gray',
                        'failed' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('paid_at')->label('Tgl Bayar')->dateTime('d M Y H:i'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'paid' => 'Terbayar',
                    'expired' => 'Kadaluarsa',
                    'failed' => 'Gagal',
                ]),
                Tables\Filters\SelectFilter::make('provider')->options([
                    'xendit' => 'Xendit',
                    'manual' => 'Manual',
                ]),
            ])
            ->actions([Actions\EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListApplicationPayments::route('/'),
            'create' => CreateApplicationPayment::route('/create'),
            'edit' => EditApplicationPayment::route('/{record}/edit'),
        ];
    }
}
