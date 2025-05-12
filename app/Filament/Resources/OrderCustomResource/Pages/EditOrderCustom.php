<?php

namespace App\Filament\Resources\OrderCustomResource\Pages;

use App\Filament\Resources\OrderCustomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderCustom extends EditRecord
{
    protected static string $resource = OrderCustomResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
