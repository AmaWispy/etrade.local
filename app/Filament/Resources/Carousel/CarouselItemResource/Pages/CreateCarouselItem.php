<?php

namespace App\Filament\Resources\Carousel\CarouselItemResource\Pages;

use App\Filament\Resources\Carousel\CarouselItemResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCarouselItem extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = CarouselItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
