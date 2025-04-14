<?php

namespace App\Filament\Resources\Carousel\CarouselItemResource\Pages;

use App\Filament\Resources\Carousel\CarouselItemResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCarouselItems extends ListRecords
{
    protected static string $resource = CarouselItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
