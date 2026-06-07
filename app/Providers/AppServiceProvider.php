<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\Setting;

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
        Schema::defaultStringLength(191);
        
        // Share settings globally
        View::composer('*', function ($view) {
            $view->with([
                'siteSettings' => [
                    'site_name' => Setting::get('site_name', 'E-Commerce Store'),
                    'site_logo' => Setting::get('site_logo', ''),
                    'site_favicon' => Setting::get('site_favicon', ''),
                    'site_description' => Setting::get('site_description', ''),
                    'contact_email' => Setting::get('contact_email', ''),
                ]
            ]);
        });
    }
}
