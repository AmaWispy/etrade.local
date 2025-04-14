<?php

namespace App\Filament\Resources\Shop\AttributeValueResource\Pages;

use App\Filament\Resources\Shop\AttributeValueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttributeValues extends ListRecords
{
    protected static string $resource = AttributeValueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
