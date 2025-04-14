<?php

namespace App\Filament\Resources\Navigation\ItemResource\Pages;

use App\Filament\Resources\Navigation\ItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditItem extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = ItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
