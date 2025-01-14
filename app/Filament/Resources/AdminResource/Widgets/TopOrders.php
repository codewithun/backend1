<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use App\Models\Store;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class TopOrders extends BaseWidget
{
    protected static ?string $heading = 'Ranking Penjualan';
    protected function getTableQuery(): Builder
    {
        return Store::query()
            ->join('orders', 'stores.user_id', '=', 'orders.user_id')
            ->select('stores.*')
            ->selectRaw('SUM(orders.total) as total_sales')
            ->groupBy('stores.id')
            ->orderByDesc('total_sales')
            ->limit(10);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('nama_usaha')
                ->label('Nama Pemilik')
                ->sortable()
                ->searchable(),
            TextColumn::make('total_sales')
                ->label('Total Penjualan')
                ->sortable()
                ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),
        ];
    }
}
