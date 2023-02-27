<?php

namespace App\Filament\Resources\ProductCategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $label = 'المنتجات';

    protected static ?string $pluralLabel = 'المنتجات';

    public static function form(Form $form): Form
    {
        return $form
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
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('المنتج'),
                Tables\Columns\TextColumn::make('selling_price')
                    ->label('سعر البيع'),
                Tables\Columns\TextColumn::make('purchasing_price')
                    ->label('سعر الشراء'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
