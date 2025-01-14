<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class UserChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penjualan';

    public ?string $filter = 'week';

    protected function getFilters(): ?array
    {
        return [
            'day' => 'Hari Ini',
            'week' => 'Minggu Ini',
            'month' => 'Bulan Ini',
        ];
    }

    protected function getData(): array
    {
        $data = match ($this->filter) {
            'day' => $this->getDayData(),
            'week' => $this->getWeekData(),
            'month' => $this->getMonthData(),
            default => $this->getWeekData(),
        };

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $data['values'],
                    'borderColor' => '#f59e0b',
                    'fill' => false,
                ]
            ],
            'labels' => $data['labels'],
        ];
    }

    protected function getDayData(): array
    {
        $today = Carbon::today();
        $hours = collect(range(0, 23));

        $sales = Order::whereDate('created_at', $today)
            ->get()
            ->groupBy(fn($order) => $order->created_at->format('H'))
            ->map(fn($orders) => $orders->sum('total'));

        return [
            'labels' => $hours->map(fn($hour) => sprintf('%02d:00', $hour))->toArray(),
            'values' => $hours->map(fn($hour) => $sales[$hour] ?? 0)->toArray(),
        ];
    }

    protected function getWeekData(): array
    {
        $start = Carbon::now()->startOfWeek();
        $end = Carbon::now()->endOfWeek();
        $days = collect(CarbonPeriod::create($start, $end));

        $sales = Order::whereBetween('created_at', [$start, $end])
            ->get()
            ->groupBy(fn($order) => $order->created_at->format('Y-m-d'))
            ->map(fn($orders) => $orders->sum('total'));

        return [
            'labels' => $days->map(fn($date) => $date->format('D'))->toArray(),
            'values' => $days->map(fn($date) => $sales[$date->format('Y-m-d')] ?? 0)->toArray(),
        ];
    }

    protected function getMonthData(): array
    {
        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $days = collect(CarbonPeriod::create($start, $end));

        $sales = Order::whereBetween('created_at', [$start, $end])
            ->get()
            ->groupBy(fn($order) => $order->created_at->format('Y-m-d'))
            ->map(fn($orders) => $orders->sum('total'));

        return [
            'labels' => $days->map(fn($date) => $date->format('d M'))->toArray(),
            'values' => $days->map(fn($date) => $sales[$date->format('Y-m-d')] ?? 0)->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
