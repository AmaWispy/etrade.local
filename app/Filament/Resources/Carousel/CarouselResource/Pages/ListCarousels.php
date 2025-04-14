<?php

namespace App\Filament\Resources\Carousel\CarouselResource\Pages;

use App\Filament\Resources\Carousel\CarouselResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCarousels extends ListRecords
{
    protected static string $resource = CarouselResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
