<?php

namespace App\Filament\Resources\OrderCustomResource\Pages;

use App\Filament\Resources\OrderCustomResource;
use App\Filament\Resources\OrderCustomResource\Widgets\OrderCustomStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderCustoms extends ListRecords
{
    protected static string $resource = OrderCustomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderCustomStats::class,
        ];
    }
}
