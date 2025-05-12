<?php

namespace App\Filament\Resources\OrderCustomResource\Widgets;

use App\Filament\Resources\OrderCustomResource\Pages\ListOrderCustoms;
use App\Models\OrderCustom;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class OrderCustomStats extends BaseWidget
{
    use InteractsWithPageTable;

    protected static ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListOrderCustoms::class;
    }

    protected function getStats(): array
    {
        $orderData = Trend::model(OrderCustom::class)
            ->between(
                start: now()->subYear(),
                end: now(),
            )
            ->perMonth()
            ->count();

        return [
            Stat::make('Orders', $this->getPageTableQuery()->count())
                ->chart(
                    $orderData
                        ->map(fn (TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),
            Stat::make('Open orders', $this->getPageTableQuery()->where('status', 'processing')->count()),
            Stat::make('Average price', number_format($this->getPageTableQuery()->avg('total'), 2)),
        ];
    }
} 