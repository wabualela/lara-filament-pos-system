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
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $label = 'طلب الشراء';

    protected static ?string $pluralLabel = 'طلبات الشراء';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label(trans('Number')),
                TextColumn::make('customer.name')
                    ->label(trans('Customer Name')),
                BadgeColumn::make('status')
                    ->label(trans('Status'))
                    ->colors([
                        'danger' => 'cancelled',
                        'warning' => 'processing',
                        'success' => fn ($state) => in_array($state, ['delivered', 'shipped']),
                    ]),
                TextColumn::make('total_price')
                    ->label(trans('Total Price')),
                TextColumn::make('items_count')
                    ->counts('items')
                    ->label(trans('Product Count')),
            ])
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
        if ($section === 'items')
            return [
                Repeater::make('items')
                    ->label(trans('Items'))
                    ->relationship()
                    ->schema([
                        Select::make('product_id')
                            ->label(trans('Product'))
                            ->options(Product::query()->pluck('name', 'id'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('unit_price', Product::find($state)?->purchasing_price ?? 0))
                            ->columnSpan(['md' => 5]),
                        TextInput::make('qty')
                            ->label(trans('gty'))
                            ->numeric()
                            ->default(1)
                            ->columnSpan(['md' => 2])
                            ->required(),
                        TextInput::make('unit_price')
                            ->label(trans('Unit Price'))
                            ->disabled()
                            ->numeric()
                            ->required()
                            ->columnSpan(['md' => 3]),
                    ])
                    ->orderable()
                    ->defaultItems(1)
                    ->disableLabel()
                    ->columns(['md' => 10])
                    ->required(),
            ];

        return [
            TextInput::make('number')
                ->label(trans('Order Number'))
                ->default('OR-' . random_int(100000, 999999))
                ->disabled()
                ->required(),
            Select::make('customer_id')
                ->label(trans('Custmoer'))
                ->relationship('customer', 'name')
                ->searchable()
                ->required()
                ->createOptionForm([
                    TextInput::make('name')
                        ->label(trans('app.customer_name'))
                        ->required(),
                    TextInput::make('email')
                        ->label(trans('app.email'))
                        ->email()
                        ->required(),
                    TextInput::make('phone')
                        ->label(trans('app.phone')),
                    Select::make('gender')
                        ->label(trans('app.gender'))
                        ->options(['male' => trans('app.male'), 'female' => trans('app.female')])
                        ->searchable()
                        ->required(),
                ])
                ->createOptionAction(
                    function (Action $action) {
                        return $action
                            ->modalHeading(trans('Add new customer'))
                            ->modalButton(trans('Create'))
                            ->modalWidth('lg');
                    }
                ),

            Select::make('status')
                ->label(trans('Status'))
                ->options([
                    'new' => trans('New'),
                    'processing' => trans('Processing'),
                    'cancelled' => trans('Cancelled'),
                ])
                ->required(),
            MarkdownEditor::make('notes')
                ->label(trans('Note'))
                ->columnSpan('full'),
        ];
    }
}
