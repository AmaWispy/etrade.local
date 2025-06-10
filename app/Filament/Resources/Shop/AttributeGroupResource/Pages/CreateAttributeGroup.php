<?php

namespace App\Filament\Resources\Shop\AttributeGroupResource\Pages;

use App\Filament\Resources\Shop\AttributeGroupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAttributeGroup extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = AttributeGroupResource::class;

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
} 