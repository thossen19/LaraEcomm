<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
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
        view()->composer('*', function ($view) {
            // Cache settings within the same request to avoid multiple DB calls
            static $cachedSettings = null;
            
            if ($cachedSettings === null) {
                $cachedSettings = [
                    'header_logo' => \App\Models\Setting::getValue('header_logo'),
                    'footer_logo' => \App\Models\Setting::getValue('footer_logo'),
                ];
            }
            
            $view->with('headerLogo', $cachedSettings['header_logo']);
            $view->with('footerLogo', $cachedSettings['footer_logo']);
        });
    }
}
