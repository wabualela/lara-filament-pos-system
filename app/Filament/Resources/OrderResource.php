<?php

namespace App\Filament\Resources;

use Akaunting\Money\Currency;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\PaymentsRelationManager;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use App\Models\Order;
use App\Models\Product;
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

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $label = 'الطلب';

    protected static ?string $pluralLabel = 'الطلبات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                [
                Card::make()
                    ->schema(
                        [

                        Grid::make(3)
                            ->schema(
                                [

                                Select::make('client_id')
                                    ->label('العميل')
                                    ->createOptionForm(
                                        [
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
                                        ]
                                    )
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
                                ]
                            ),

                        Grid::make()
                            ->schema(
                                [
                                TextInput::make('total')
                                    ->suffix('جنيه')
                                    ->default(0)
                                    ->numeric(),
                                TextInput::make('sub_total')
                                    ->suffix('جنيه')
                                    ->default(0)
                                    ->numeric(),
                                ]
                            ),

                        Grid::make(3)
                            ->schema(
                                [
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
                                ]
                            ),

                        Select::make('payment_method')
                            ->label('الدفع عن طريق')
                            ->options(
                                [
                                'كاش',
                                'بنكك',
                                ]
                            ),

                        ]
                    )
                ]
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(
                [
                //
                ]
            )
            ->filters(
                [
                //
                ]
            )
            ->actions(
                [
                Tables\Actions\EditAction::make(),
                ]
            )
            ->bulkActions(
                [
                Tables\Actions\DeleteBulkAction::make(),
                ]
            );
    }

    public static function getRelations(): array
    {
        return [
            PaymentsRelationManager::class
        ];
    }

    public static function getWidgets(): array
    {
        return [
            OrderStats::class,
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScope(SoftDeletingScope::class);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['number', 'client.name'];
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::$model::where('status', 'new')->count();
    }

    public static function getFormSchema(?string $section = null): array
    {
        if ($section === 'items') {
            return [
                Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->schema(
                        [
                        Forms\Components\Select::make('product_id')
                            ->label('Product')
                            ->options(Product::query()->pluck('name', 'id'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('unit_price', Product::find($state)?->price ?? 0))
                            ->columnSpan(
                                [
                                'md' => 5,
                                ]
                            ),

                        Forms\Components\TextInput::make('qty')
                            ->numeric()
                            ->default(1)
                            ->columnSpan(
                                [
                                'md' => 2,
                                ]
                            )
                            ->required(),

                        Forms\Components\TextInput::make('unit_price')
                            ->label('Unit Price')
                            ->disabled()
                            ->numeric()
                            ->required()
                            ->columnSpan(
                                [
                                'md' => 3,
                                ]
                            ),
                        ]
                    )
                    ->orderable()
                    ->defaultItems(1)
                    ->disableLabel()
                    ->columns(
                        [
                        'md' => 10,
                        ]
                    )
                    ->required(),
            ];
        }

        return [
            Forms\Components\TextInput::make('number')
                ->default('OR-' . random_int(100000, 999999))
                ->disabled()
                ->required(),

            Forms\Components\Select::make('customer_id')
                ->relationship('customer', 'name')
                ->searchable()
                ->required()
                ->createOptionForm(
                    [
                    Forms\Components\TextInput::make('name')
                        ->label(__('app.customer_name'))
                        ->required(),
                        Forms\Components\TextInput::make('email')
                            ->label(__('app.email'))
                            ->email()    
                            ->required(),
                    Forms\Components\TextInput::make('phone')
                        ->label(__('app.phone')),
                    Forms\Components\Select::make('gender')
                            ->label(__('app.gender'))
                            ->options(['male' => __('app.male'), 'female' => __('app.female')])
                            ->searchable()
                            ->required(),
                    ]
                )
                ->createOptionAction(
                    function (Forms\Components\Actions\Action $action) {
                        return $action
                            ->modalHeading('Create customer')
                            ->modalButton('Create customer')
                            ->modalWidth('lg');
                    }
                ),

            Forms\Components\Select::make('status')
                ->options(
                    [
                    'new' => 'New',
                    'processing' => 'Processing',
                    'shipped' => 'Shipped',
                    'delivered' => 'Delivered',
                    'cancelled' => 'Cancelled',
                    ]
                )
                ->required(),

            Forms\Components\MarkdownEditor::make('notes')
                ->columnSpan('full'),
        ];
    }
}
