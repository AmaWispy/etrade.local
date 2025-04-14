<?php

namespace App\Filament\Resources\Carousel\CarouselResource\Pages;

use App\Filament\Resources\Carousel\CarouselResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCarousel extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = CarouselResource::class;
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
        ];
    }
}
