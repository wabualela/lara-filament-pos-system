<?php

namespace App\Filament\Resources\ProductCategoryResource\Widgets;

use App\Models\ProductCategory;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class ProductCategoryStats extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Products', ProductCategory::count()),
        ];
    }
}
