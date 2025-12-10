<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use App\Models\ProductCart;

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
        Paginator::useBootstrapFive();

        // Share cart count with header on all pages
        View::composer('layouts.header', function ($view) {
            $count = 0;
            if (Auth::check()) {
                $count = ProductCart::where('user_id', Auth::id())->count();
            }
            $view->with('cartCount', $count);
        });
    }
}
