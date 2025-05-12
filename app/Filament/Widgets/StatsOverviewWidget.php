<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\OrderCustom;
use App\Models\Shop\Customer;
use Carbon\Carbon;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        

        return [
            $this->getRevenueStat(),
            $this->getOrdersStat(),
            $this->getCustomersStat(),
        ];
    }

    /**
     * Calculate total revenue for current month
     * Calculate difference from previous month
     */
    protected function getRevenueStat()
    {
        /**
         * Total revenue for current month
         */
        $currentMonth = OrderCustom::whereYear('created_at', '=', Carbon::now()->year)
            ->whereMonth('created_at', '=', Carbon::now()->month)
            ->where('status', OrderCustom::PROCESSING)
            ->sum('total');

        /**
         * Total revenue for previous month
         */
        $previousMonth = OrderCustom::whereYear('created_at', '=', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
            ->where('status', OrderCustom::PROCESSING)
            ->sum('total');

        /**
         * Difference in revenue
         */
        $difference = $currentMonth - $previousMonth;
        if($previousMonth == 0){
            $percentageDifference = $difference * 100;
        } else {    
            $percentageDifference = ($difference / $previousMonth) * 100;
        }
        
        return $this->drawStatChart('Revenue', $currentMonth, $percentageDifference);
    }

    /**
     * Count orders placed in current month
     * Calculate difference from previous month
     */
    protected function getOrdersStat()
    {
        /**
         * Total orders for current month
         */
        $currentMonth = OrderCustom::whereYear('created_at', '=', Carbon::now()->year)
            ->whereMonth('created_at', '=', Carbon::now()->month)
            ->count();

        /**
         * Total orders for previous month
         */
        $previousMonth = OrderCustom::whereYear('created_at', '=', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
            ->count();

        /**
         * Difference in orders
         */
        $difference = $currentMonth - $previousMonth;
        if($previousMonth == 0){
            $percentageDifference = $difference * 100;
        } else {    
            $percentageDifference = ($difference / $previousMonth) * 100;
        }
        
        return $this->drawStatChart('Orders', $currentMonth, $percentageDifference);
    }

    /**
     * Count tickets sold in current month
     * Calculate difference from previous month
     */
    protected function getCustomersStat()
    {
        /**
         * Total tickets sold in current month
         */
        $currentMonth = Customer::whereYear('created_at', '=', Carbon::now()->year)
            ->whereMonth('created_at', '=', Carbon::now()->month)
            ->count();

        /**
         * Total tickets sold in previous month
         */
        $previousMonth = Customer::whereYear('created_at', '=', Carbon::now()->subMonth()->year)
            ->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)
            ->count();

        /**
         * Difference
         */
        $difference = $currentMonth - $previousMonth;
        if($previousMonth == 0){
            $percentageDifference = $difference * 100;
        } else {    
            $percentageDifference = ($difference / $previousMonth) * 100;
        }
        
        return $this->drawStatChart('Customers', $currentMonth, $percentageDifference);
    }

    protected function drawStatChart($title, $total, $difference)
    {
        if($difference >= 0) { 
            $description = round($difference, 2) . '% increase';
            $color = "success";
            $icon = "heroicon-m-arrow-trending-up";
        } else {
            $description = round($difference, 2) . '% descrease';
            $color = 'danger';
            $icon = "heroicon-m-arrow-trending-down";
        }

        //$month = Carbon::now()->format('F');

        return Stat::make($title, $total)
            ->description($description)
            ->descriptionIcon($icon)
            //->chart([15, 4, 10, 2, 12, 4, 12])
            ->color($color);
    }
}
