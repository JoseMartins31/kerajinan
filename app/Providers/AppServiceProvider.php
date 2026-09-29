<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Kategori;

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
        // Share categories with frontend layout only
        View::composer('layouts.frontend', function ($view) {
            $categories = Kategori::select('idKategori', 'nama_kategori')
                ->orderBy('nama_kategori')
                ->get();
            $view->with('categories', $categories);
        });
    }
}
