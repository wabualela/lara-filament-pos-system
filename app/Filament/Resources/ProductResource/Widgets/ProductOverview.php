<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\Widget;

class ProductOverview extends Widget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Products', Product::count()),
        ];
    }
}
