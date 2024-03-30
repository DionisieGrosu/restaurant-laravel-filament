<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Food;
use Illuminate\Routing\Route as RoutingRoute;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::bind('category', function ($value, RoutingRoute $route) {

            return Category::where([
                'slug' => $value,
                'is_active' => true,
            ])->firstOrFail();
        });

        Route::bind('food', function ($value, RoutingRoute $route) {

            return Food::where([
                'slug' => $value,
                'is_active' => true,
            ])->firstOrFail();
        });
    }
}
