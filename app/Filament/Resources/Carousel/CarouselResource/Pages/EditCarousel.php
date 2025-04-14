<?php

namespace App\Filament\Resources\Carousel\CarouselResource\Pages;

use App\Filament\Resources\Carousel\CarouselResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarousel extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = CarouselResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
