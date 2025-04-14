<?php

namespace App\Filament\Resources\Shop\PaymentMethodResource\Pages;

use App\Filament\Resources\Shop\PaymentMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentMethod extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = PaymentMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
