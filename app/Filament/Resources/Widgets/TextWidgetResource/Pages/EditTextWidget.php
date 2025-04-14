<?php

namespace App\Filament\Resources\Widgets\TextWidgetResource\Pages;

use App\Filament\Resources\Widgets\TextWidgetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTextWidget extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = TextWidgetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
