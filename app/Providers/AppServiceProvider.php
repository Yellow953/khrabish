<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();

        // Share parent categories with subcategories to all frontend views
        View::composer('frontend.layouts._header', function ($view) {
            $parentCategories = Category::whereNull('parent_id')
                ->with('subCategories')
                ->select('id', 'name', 'image')
                ->get();
            $view->with('parentCategories', $parentCategories);
        });
    }
}
