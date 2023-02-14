<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $label = 'الطلب';

    protected static ?string $pluralLabel = 'الطلبات';

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([

                        Grid::make(3)
                            ->schema([

                                Select::make('client_id')
                                    ->label('العميل')
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->label('اسم العميل')
                                            ->autocomplete('name')
                                            ->required(),
                                        TextInput::make('tel')
                                            ->label('رقم التلفون')
                                            ->autocomplete('tel')
                                            ->required(),
                                        TextInput::make('address')
                                            ->label('العنوان')
                                            ->autocomplete('address')
                                    ])
                                    ->searchable()
                                    ->preload()
                                    ->relationship('client', 'name'),

                                Select::make('product_id')
                                    ->label('المنتج')
                                    ->searchable()
                                    ->preload()
                                    // ->multiple()
                                    ->relationship('product', 'name'),

                                TextInput::make('quantity')
                                    ->label('الكمية')
                                    ->default(0)
                                    ->numeric(),
                            ]),

                        Grid::make()
                            ->schema([
                                TextInput::make('total')
                                    ->suffix('جنيه')
                                    ->default(0)
                                    ->numeric(),
                                TextInput::make('sub_total')
                                    ->suffix('جنيه')
                                    ->default(0)
                                    ->numeric(),
                            ]),




                        Grid::make(3)
                            ->schema([
                                TextInput::make('pay')
                                    ->suffix('جنيه')
                                    ->numeric(),
                                TextInput::make('due')
                                    ->suffix('جنيه')
                                    ->numeric(),
                                TextInput::make('vat')
                                    ->suffix('%')
                                    ->label('ضريبة')
                                    ->numeric(),
                            ]),

                        Select::make('payment_method')
                            ->label('الدفع عن طريق')
                            ->options([
                                'كاش',
                                'بنكك',
                            ]),

                        // Grid::make(3)
                        //     ->schema([
                        //         DatePicker::make('order_date'),
                        //         DatePicker::make('order_month')
                        //             ->format('m'),
                        //         DatePicker::make('order_year'),
                        //     ]),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
