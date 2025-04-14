<?php

namespace App\Filament\Resources\Shop\ShippingZoneResource\Pages;

use App\Filament\Resources\Shop\ShippingZoneResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListShippingZones extends ListRecords
{
    protected static string $resource = ShippingZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
