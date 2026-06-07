<?php

namespace App\Providers;

use App\Helpers\CurrencyHelper;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class CurrencyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Create @currency directive
        Blade::directive('currency', function ($expression) {
            return "<?php echo App\\Helpers\\CurrencyHelper::format($expression); ?>";
        });

        // Create @currencySymbol directive
        Blade::directive('currencySymbol', function ($expression) {
            return "<?php echo App\\Helpers\\CurrencyHelper::getSymbol($expression); ?>";
        });

        // Share currency helper with all views
        view()->share('currencyHelper', new CurrencyHelper());
    }
}
