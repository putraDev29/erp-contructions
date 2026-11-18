<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

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
        // Blade directive: @rupiah($amount)
        Blade::directive('rupiah', function ($expression) {
            return "<?php echo 'Rp ' . number_format((float) ($expression), 0, ',', '.'); ?>";
        });

        // Blade directive: @dateid($date)
        Blade::directive('dateid', function ($expression) {
            return "<?php echo ($expression) ? \Carbon\Carbon::parse($expression)->translatedFormat('d M Y') : '-'; ?>";
        });
    }
}
