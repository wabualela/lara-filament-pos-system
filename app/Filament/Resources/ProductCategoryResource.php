<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductCategoryResource\Pages;
use App\Filament\Resources\ProductCategoryResource\RelationManagers;
use App\Models\ProductCategory;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductCategoryResource extends Resource
{
    protected static ?string $model = ProductCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $label = 'نوع المنتج';

    protected static ?string $pluralLabel = 'انواع المنتجات';

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    protected static ?string $navigationGroup = 'المنتجات';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->label('اسم نوع المنتج')
                            ->required()
                            ->autofocus()
                            ->autocomplete()
                            ->alpha()
                            ->minLength(3)
                            ->maxLength(255)
                            ->validationAttribute('اسم نوع المنتج')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->circular(),
                TextColumn::make('name')
                    ->label('اسم نوع المنتج')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('products_count')->counts('products')
                    ->label('عدد المنتجات')
                    ->sortable()
                    ->searchable(),
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
            'index' => Pages\ListProductCategories::route('/'),
            'create' => Pages\CreateProductCategory::route('/create'),
            'edit' => Pages\EditProductCategory::route('/{record}/edit'),
        ];
    }
}
