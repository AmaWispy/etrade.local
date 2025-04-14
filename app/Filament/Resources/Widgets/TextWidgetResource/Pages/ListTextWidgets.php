<?php

namespace App\Filament\Resources\Widgets\TextWidgetResource\Pages;

use App\Filament\Resources\Widgets\TextWidgetResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTextWidgets extends ListRecords
{
    protected static string $resource = TextWidgetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
