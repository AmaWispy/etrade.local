<?php

namespace App\Filament\Resources\Shop\AttributeGroupResource\Pages;

use App\Filament\Resources\Shop\AttributeGroupResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAttributeGroups extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = AttributeGroupResource::class;

    protected function getActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // Можно добавить виджет со статистикой
        ];
    }
} 