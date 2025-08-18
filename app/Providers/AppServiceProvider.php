<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Illuminate\Pagination\Paginator;
use App\Models\Category;
use Illuminate\Support\Facades\View;

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
        //
        // Paginator::useBootstrapFive(); // Hoặc useBootstrapFour()

        // Lấy danh mục cha + toàn bộ con đệ quy
        View::composer('client.partials.navbar', function ($view) {
            $menuCategories = Category::with('childrenRecursive')
                ->whereNull('parent_id')
                ->get();

            $view->with('menuCategories', $menuCategories);
        });
    }
}
