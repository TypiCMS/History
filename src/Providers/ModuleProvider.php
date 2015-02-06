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
        // Bring in the routes
        require __DIR__ . '/../routes.php';

        // Add dirs
        View::addNamespace('history', __DIR__ . '/../views/');
        $this->publishes([
            __DIR__ . '/../config/' => config_path('typicms/history'),
        ], 'config');
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
