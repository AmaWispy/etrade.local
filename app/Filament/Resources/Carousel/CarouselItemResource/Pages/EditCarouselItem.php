<?php

namespace App\Filament\Resources\Carousel\CarouselItemResource\Pages;

use App\Filament\Resources\Carousel\CarouselItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCarouselItem extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = CarouselItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
