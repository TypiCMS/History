<?php

namespace TypiCMS\Modules\History\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to the controller routes in your routes file.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'TypiCMS\Modules\History\Http\Controllers';

    /**
     * Define the routes for the application.
     *
     * @return null
     */
    public function map()
    {
        Route::namespace($this->namespace)->group(function (Router $router) {

            /*
             * Admin routes
             */
            $router->middleware('admin')->prefix('admin')->group(function (Router $router) {
                $router->get('history', 'AdminController@index')->name('admin::index-history')->middleware('can:see-history');
                $router->delete('history', 'AdminController@destroy')->name('admin::destroy-history')->middleware('can:clear-history');
            });
        });
    }
}
