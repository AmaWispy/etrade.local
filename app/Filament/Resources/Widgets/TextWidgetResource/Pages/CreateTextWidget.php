<?php

namespace App\Filament\Resources\Widgets\TextWidgetResource\Pages;

use App\Filament\Resources\Widgets\TextWidgetResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTextWidget extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = TextWidgetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
