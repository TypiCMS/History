<?php
namespace TypiCMS\Modules\History\Providers;

use Config;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\History\Models\History;
use TypiCMS\Modules\History\Repositories\CacheDecorator;
use TypiCMS\Modules\History\Repositories\EloquentHistory;
use TypiCMS\Modules\Core\Services\Cache\LaravelCache;
use View;

class ModuleProvider extends ServiceProvider
{

    public function boot()
    {

        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php', 'typicms.history'
        );

        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'history');

        $this->publishes([
            __DIR__ . '/../resources/views' => base_path('resources/views/vendor/history'),
        ], 'views');
        $this->publishes([
            __DIR__ . '/../database' => base_path('database'),
        ], 'migrations');

        AliasLoader::getInstance()->alias(
            'History',
            'TypiCMS\Modules\History\Facades\Facade'
        );
    }

    public function register()
    {

        $app = $this->app;

        /**
         * Register route service provider
         */
        $app->register('TypiCMS\Modules\History\Providers\RouteServiceProvider');

        $app->bind('TypiCMS\Modules\History\Repositories\HistoryInterface', function (Application $app) {
            $repository = new EloquentHistory(new History);
            if (! config('typicms.cache')) {
                return $repository;
            }
            $laravelCache = new LaravelCache($app['cache'], ['history'], 10);

            return new CacheDecorator($repository, $laravelCache);
        });
    }
}
