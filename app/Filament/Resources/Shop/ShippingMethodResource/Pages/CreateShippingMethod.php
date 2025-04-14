<?php

namespace App\Filament\Resources\Shop\ShippingMethodResource\Pages;

use App\Filament\Resources\Shop\ShippingMethodResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateShippingMethod extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = ShippingMethodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
