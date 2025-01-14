<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Carbon\Carbon;

class DateTime extends BaseWidget
{
    // Make polling interval nullable (for refreshing)
    protected static ?string $pollingInterval = '1s';

    protected function getCards(): array
    {
        $now = Carbon::now('Asia/Jakarta');
        $monthProgress = $this->getMonthProgress(); // Get progress of the current month

        return [
            // Card for Current Time
            Card::make('Current Time', $now->format('H:i:s'))
                ->description('Current local time')
                ->descriptionIcon('heroicon-o-clock')
                ->icon('heroicon-o-clock')
                ->color('success'),

            // Card for Today’s Date
            Card::make('Today', $now->format('d F Y'))
                ->description($now->format('l'))
                ->descriptionIcon('heroicon-o-calendar')
                ->icon('heroicon-o-calendar')
                ->color('primary'),

            // Card for Month Progress
            Card::make('Month Progress', $monthProgress . '%')
                ->description('Percentage of the month that has passed')
                ->descriptionIcon('heroicon-o-calendar')
                ->icon('heroicon-o-calendar')
                ->color('info'),
        ];
    }

    private function getMonthProgress()
    {
        $now = Carbon::now('Asia/Jakarta');
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();
        $daysInMonth = $endOfMonth->diffInDays($startOfMonth);
        $daysPassed = $now->diffInDays($startOfMonth);

        return round(($daysPassed / $daysInMonth) * 100); // Calculate percentage progress of the month
    }
}
