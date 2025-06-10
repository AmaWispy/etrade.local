<?php

namespace App\Filament\Resources\Shop\AttributeGroupResource\Pages;

use App\Filament\Resources\Shop\AttributeGroupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttributeGroup extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = AttributeGroupResource::class;

    protected function getActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
} 