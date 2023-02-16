<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\ProductCategory;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $label = 'المنتج';

    protected static ?string $pluralLabel = 'المنتجات';

    protected static ?string $navigationGroup = 'المنتجات';

    protected static ?int $navigationSort = 1;

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
                        Grid::make()
                            ->schema([
                                TextInput::make('name')
                                    ->label('اسم المنتج')
                                    ->required(),
                                Select::make('product_categories_id')
                                    ->label('نوع المنتج')
                                    ->relationship('category', 'name')
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->label('اسم نوع المنتج')
                                    ])
                                    ->searchable()
                                    ->required(),
                            ]),
                        Grid::make()
                            ->schema([
                                TextInput::make('quantity')
                                    ->label('الكمية')
                                    ->numeric(),
                                Select::make('units_id')
                                    ->label('الوحدة')
                                    ->relationship('unit', 'name')
                                    ->preload()
                                    ->createOptionForm([
                                        TextInput::make('name')
                                            ->required()
                                            ->label('اسم الوحدة')
                                    ])
                                    ->searchable()
                                    ->required(),
                            ]),
                        Grid::make()
                            ->schema([
                                TextInput::make('selling_price')
                                    ->label('سعر البيع')
                                    ->required()
                                    ->suffix('جنيه')
                                    ->numeric(),
                                TextInput::make('purchasing_price')
                                    ->label('سعر الشراء')
                                    ->required()
                                    ->suffix('جنيه')
                                    ->numeric(),
                            ]),
                        Textarea::make('note')
                            ->label('ملاحظات')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('اسم المنتج')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label('نوع المنتج')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('quantity')
                    ->label('الكمية')
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
