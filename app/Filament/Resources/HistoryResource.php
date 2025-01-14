<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HistoryResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class HistoryResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'History Orders';
    protected static ?int $navigationSort = 3;
    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Pemilik')
                    ->searchable(),

                TextColumn::make('items.name')
                    ->label('Items')
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->expandableLimitedList(),

                TextColumn::make('items.quantity')
                    ->label('Jumlah')
                    ->listWithLineBreaks()
                    ->limitList(3)
                    ->expandableLimitedList(),

                TextColumn::make('total')
                    ->label('Total')
                    ->money('idr')
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('Metode Pembayaran')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i', timezone: 'Asia/Jakarta')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Detail Order')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Pemilik Toko')
                            ->relationship(
                                'user',
                                'name',
                                fn($query) => $query->orderBy('created_at', 'desc')
                            )
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('payment_method')
                            ->label('Metode Pembayaran')
                            ->disabled(),
                        Forms\Components\TextInput::make('total')
                            ->label('Total')
                            ->disabled(),
                        Forms\Components\TextInput::make('created_at')
                            ->label('Tanggal')
                            ->formatStateUsing(fn($state) => Carbon::parse($state)
                                ->timezone('Asia/Jakarta')
                                ->format('d M Y, H:i') . ' WIB')
                            ->disabled(),
                    ]),
                Forms\Components\Section::make('Items')
                    ->schema([
                        Forms\Components\Placeholder::make('order_items')
                            ->label('Details')
                            ->content(function ($record) {
                                return $record->items->map(function ($item) {
                                    return "Nama Item: {$item->name}, Jumlah: {$item->quantity} |";
                                })->implode("\n");
                            }),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHistories::route('/'),
            'view' => Pages\ViewHistory::route('/{record}'),

        ];
    }
}
