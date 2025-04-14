<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use App\Models\Settings;

class CustomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Cache for 1440 minutes (24 hours)
            $templateSettings = Cache::remember('templateSettings', 1440, function () {
                return Settings::select('key', 'content', 'image')->get()->mapWithKeys(function ($model) {
                    $result = [
                        $model->key => $model->content,
                        $model->key . '-image' => isset($model->image) ? url('storage/'.$model->image) : null
                    ];
                    
                    // $value = null !== $model->image ? url('storage/'.$model->image) : $model->content;
                    // return [$model->key => $value];
                    return $result;
                });
            });
            $view->with('templateSettings', $templateSettings);
        });

        
    }
}
