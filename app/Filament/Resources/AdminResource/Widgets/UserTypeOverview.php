<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Models\Order;
use App\Models\User;
use App\Models\Store;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class UserTypeOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '15s';

    protected function getStats(): array
    {
        $today = Carbon::today();

        return [
            Stat::make('Total Penjualan Hari Ini', 'Rp ' . number_format(
                Order::whereDate('created_at', $today)->sum('total'),
                0,
                ',',
                '.'
            ))
                ->description('Total omzet transaksi')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success')
                ->chart([7, 4, 6, 8, 5, 2, 3]),

            Stat::make(
                'Jumlah Transaksi Hari Ini',
                Order::whereDate('created_at', $today)->count()
            )
                ->description('Transaksi berhasil')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('warning')
                ->chart([2, 4, 6, 8, 10, 12, 14]),

            Stat::make(
                'Jumlah User Terdaftar',
                User::count()
            )
                ->description('Total pengguna')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([1, 3, 5, 7, 9, 11, 13]),

            Stat::make(
                'Jumlah Outlet Aktif',
                Store::whereDate('created_at', '<=', $today)->count()
            )
                ->description('Outlet beroperasi')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('info')
                ->chart([3, 6, 9, 12, 15, 18, 21]),
        ];
    }
}
