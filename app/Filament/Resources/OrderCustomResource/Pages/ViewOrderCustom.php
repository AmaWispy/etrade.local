<?php

namespace App\Filament\Resources\OrderCustomResource\Pages;

use App\Filament\Resources\OrderCustomResource;
use Filament\Resources\Pages\Page;
use Filament\Actions;

class ViewOrderCustom extends Page
{
    protected static string $resource = OrderCustomResource::class;
    protected static string $view = 'filament.resources.order-custom-resource.view';

    public $items;

    public function mount($record): void
    {
        $this->record = static::getResource()::resolveRecordRouteBinding($record);
        $this->items = $this->record->cart?->items ?? collect();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
} 