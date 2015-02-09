<?php
namespace TypiCMS\Modules\History\Providers;

use Config;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\History\Models\History;
use TypiCMS\Modules\History\Repositories\CacheDecorator;
use TypiCMS\Modules\History\Repositories\EloquentHistory;
use TypiCMS\Services\Cache\LaravelCache;
use View;

class ModuleProvider extends ServiceProvider
{

    public function boot()
    {
        // Add dirs
        View::addNamespace('history', __DIR__ . '/../views/');
        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php', 'typicms.history'
        );
        $this->publishes([
            __DIR__ . '/../migrations/' => base_path('/database/migrations'),
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
            if (! Config::get('app.cache')) {
                return $repository;
            }
            $laravelCache = new LaravelCache($app['cache'], ['history'], 10);

            return new CacheDecorator($repository, $laravelCache);
        });
    }
}
