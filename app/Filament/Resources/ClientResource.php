<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Filament\Resources\ClientResource\Widgets\ClientOverview;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $label = 'عميل';

    protected static ?string $pluralLabel = 'العملاء';

    protected static ?string $navigationGroup = 'المستخدمين';

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
                                    ->label('اسم العميل')
                                    ->autofocus()
                                    ->required(),
                                TextInput::make('tel')
                                    ->label('رقم التلفون')
                                    ->autocomplete('tel')
                                    ->required()
                            ]),
                        TextInput::make('address')
                            ->label('العنوان')
                            ->autocomplete('address')
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('اسم العميل')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('tel')
                    ->sortable()
                    ->label('رقم التلفون')
                    ->searchable(),
                TextColumn::make('address')
                    ->sortable()
                    ->label('العنوان')
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
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            ClientOverview::class
        ];
    }
}
