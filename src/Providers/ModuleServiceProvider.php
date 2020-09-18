<?php

namespace TypiCMS\Modules\History\Providers;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\History\Facades\History as HistoryFacade;
use TypiCMS\Modules\History\Models\History;

class ModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'typicms.history');

        $this->publishes([
            __DIR__.'/../database/migrations/create_history_table.php.stub' => getMigrationFileName('create_history_table'),
        ], 'migrations');

        AliasLoader::getInstance()->alias('History', HistoryFacade::class);
    }

    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register(RouteServiceProvider::class);

        $app->bind('History', History::class);
    }
}
