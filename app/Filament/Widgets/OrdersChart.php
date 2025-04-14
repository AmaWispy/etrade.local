<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Shop\Order;
use Carbon\Carbon;

class OrdersChart extends ChartWidget
{
    protected static ?string $heading = 'Orders per month';

    protected static ?int $sort = 1;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $months = [];
        $totals = [];

        $currentMonth = Carbon::now()->startOfMonth();

        // Calculate totals for the last 12 months
        $tickets = Order::selectRaw('DATE_FORMAT(created_at, "%b") as month_name, COUNT(*) as orders')
            ->where('created_at', '>=', $currentMonth->copy()->subMonths(11)) // Starting from 12 months ago
            ->groupBy('month_name')
            ->get();

        foreach ($tickets as $month) {
            $months[] = $month->month_name;
            $totals[] = $month->orders;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $totals,
                    'fill' => 'start',
                ],
            ],
            'labels' => $months,
        ];
    }
}
