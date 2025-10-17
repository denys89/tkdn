<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\StringHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Contracts\CodeGenerationServiceInterface::class,
            \App\Services\CodeGenerationService::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register StringHelper as a global Blade directive
        Blade::directive('safeLimit', function ($expression) {
            return "<?php echo App\Helpers\StringHelper::safeLimit($expression); ?>";
        });
    }
}
