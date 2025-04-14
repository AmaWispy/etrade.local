<?php

namespace App\Filament\Resources\Widgets\WidgetGroupResource\Pages;

use App\Filament\Resources\Widgets\WidgetGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWidgetGroup extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = WidgetGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
