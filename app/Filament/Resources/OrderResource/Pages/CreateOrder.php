<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Wizard\Step;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateOrder extends CreateRecord
{
    use HasWizard;

    protected static string $resource = OrderResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('Order Details')
                ->label(trans('Order Details'))
                ->schema([
                    Card::make(OrderResource::getFormSchema())->columns(),
                ]),

            Step::make('Order Items')
                ->label(trans('Order Details'))
                ->schema([
                    Card::make(OrderResource::getFormSchema('items')),
                ]),
        ];
    }
}
