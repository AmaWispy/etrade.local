<?php

namespace App\Filament\Resources\Widgets\WidgetGroupResource\Pages;

use App\Filament\Resources\Widgets\WidgetGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWidgetGroups extends ListRecords
{
    protected static string $resource = WidgetGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
