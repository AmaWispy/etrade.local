<?php

namespace App\Filament\Resources\Shop\AttributeValueResource\Pages;

use App\Filament\Resources\Shop\AttributeValueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttributeValue extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = AttributeValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
