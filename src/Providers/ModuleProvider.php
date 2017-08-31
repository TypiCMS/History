<?php

namespace TypiCMS\Modules\History\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\History\Facades\History as HistoryFacade;
use TypiCMS\Modules\History\Models\History;
use TypiCMS\Modules\History\Repositories\EloquentHistory;

class ModuleProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'typicms.history'
        );

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'history');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/history'),
        ], 'views');

        AliasLoader::getInstance()->alias('History', HistoryFacade::class);
    }

    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register(RouteServiceProvider::class);

        $app->bind('History', EloquentHistory::class);
    }
}
