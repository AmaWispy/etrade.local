<?php

namespace App\Filament\Resources\Shop\AttributeValueResource\Pages;

use App\Filament\Resources\Shop\AttributeValueResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAttributeValue extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = AttributeValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
