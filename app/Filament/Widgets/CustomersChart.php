<?php

namespace App\Filament\Widgets;
use App\Models\OrderCustom;
use Carbon\Carbon;

use Filament\Widgets\ChartWidget;

class CustomersChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue per month';

    protected static ?int $sort = 2;

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
        $tickets = OrderCustom::selectRaw('DATE_FORMAT(created_at, "%b") as month_name, SUM(total) as revenue')
            ->where('created_at', '>=', $currentMonth->copy()->subMonths(11)) // Starting from 12 months ago
            ->groupBy('month_name')
            ->get();

        foreach ($tickets as $month) {
            $months[] = $month->month_name;
            $totals[] = $month->revenue;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Revenue',
                    'data' => $totals,
                    'fill' => 'start',
                ],
            ],
            'labels' => $months,
        ];
    }
}
