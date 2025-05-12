<?php

namespace App\Filament\Resources\OrderCustomResource\Pages;

use App\Filament\Resources\OrderCustomResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrderCustom extends CreateRecord
{
    protected static string $resource = OrderCustomResource::class;
}
