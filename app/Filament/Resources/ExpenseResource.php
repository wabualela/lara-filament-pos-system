<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExpenseResource\Pages;
use App\Filament\Resources\ExpenseResource\RelationManagers;
use App\Models\Expense;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $label = 'منصرف';

    protected static ?string $pluralLabel = 'المنصرفات';

    protected static ?string $navigationGroup = 'المنصرفات';

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
                                TextInput::make('title')
                                    ->autofocus()
                                    ->required()
                                    ->label('المنصرف'),
                                DatePicker::make('create_at')
                                    ->label('التاريخ')
                                    ->required()
                                    ->default(now())
                                    ->minDate(now()->yesterday()),
                            ]),
                        TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->autocomplete()
                            ->suffix('جنيه')
                            ->label('المبلغ'),
                        Textarea::make('details')
                            ->label('ملاجظات'),
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
            'index' => Pages\ListExpenses::route('/'),
            'create' => Pages\CreateExpense::route('/create'),
            'edit' => Pages\EditExpense::route('/{record}/edit'),
        ];
    }
}
