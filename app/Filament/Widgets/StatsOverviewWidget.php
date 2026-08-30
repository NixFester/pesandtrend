<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use App\Models\ApplicationPayment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pendaftaran Hari Ini', Application::whereDate('created_at', today())->count())
                ->icon('heroicon-o-clipboard-document-list')
                ->color('primary'),
            Stat::make('Sudah Bayar', ApplicationPayment::where('status', 'paid')->count())
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Pendapatan Bulan Ini', 'Rp '.number_format(
                ApplicationPayment::where('status', 'paid')
                    ->whereMonth('paid_at', now()->month)
                    ->sum('amount'),
                0, ',', '.'
            ))
                ->icon('heroicon-o-currency-dollar')
                ->color('warning'),
            Stat::make('Menunggu Pembayaran', ApplicationPayment::where('status', 'pending')->count())
                ->icon('heroicon-o-clock')
                ->color('danger'),
        ];
    }
}
