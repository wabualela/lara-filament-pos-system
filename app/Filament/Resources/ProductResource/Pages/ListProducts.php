<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportAction::make()
                ->fields([
                    ImportField::make('name')
                        ->label('المنتج')
                        ->required(),
                    ImportField::make('selling_price')
                        ->label('سعر البيع')
                        ->required(),
                    ImportField::make('purchasing_price')
                        ->label('سعر الشراء')
                        ->required(),
                    ImportField::make('quantity')
                        ->label('الكمية')
                ])
        ];
    }
}
