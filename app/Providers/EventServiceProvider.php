<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

use App\Models\Shop\Product;
use App\Observers\ProductObserver;
use App\Models\Shop\ProductVariation;
use App\Observers\VariationObserver;
use App\Models\Shop\Discount;
use App\Observers\DiscountObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Product::observe(ProductObserver::class);
        ProductVariation::observe(VariationObserver::class);
        Discount::observe(DiscountObserver::class);
    }
}
