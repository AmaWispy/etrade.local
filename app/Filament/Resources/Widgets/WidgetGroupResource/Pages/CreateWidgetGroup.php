<?php

namespace App\Filament\Resources\Widgets\WidgetGroupResource\Pages;

use App\Filament\Resources\Widgets\WidgetGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWidgetGroup extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;
    
    protected static string $resource = WidgetGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
