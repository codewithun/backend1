<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreResource\Pages;
use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationLabel = 'Stores';
    protected static ?int $navigationSort = 2;

    public static function canCreate(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Usaha')
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
                                Forms\Components\FileUpload::make('gambar')
                                    ->image()
                                    ->directory('store-images')
                                    ->imagePreviewHeight('20')
                                    ->panelAspectRatio('4:3')
                                    ->imageResizeMode('contain')
                                    ->columnSpanFull(),
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('nama_usaha')
                                            ->label('Nama Usaha'),
                                        Forms\Components\TextInput::make('jenis_usaha')
                                            ->label('Jenis Usaha'),
                                        Forms\Components\Textarea::make('alamat')
                                            ->label('Alamat')

                                    ]),


                            ])
                    ])
                    ->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_usaha')
                    ->searchable()

                    ->sortable(),
                TextColumn::make('jenis_usaha')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('alamat')
                    ->searchable(),
                ImageColumn::make('gambar')
                    ->label('Gambar Usaha')
                    ->alignCenter(),
                TextColumn::make('user.name')
                    ->label('Pemilik')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y, H:i', timezone: 'Asia/Jakarta')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListStores::route('/'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }
}
