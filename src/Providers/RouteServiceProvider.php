<?php

namespace TypiCMS\Modules\History\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use TypiCMS\Modules\History\Http\Controllers\ApiController;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define the routes for the application.
     */
    public function map()
    {
        Route::namespace($this->namespace)->group(function (Router $router) {
            /*
             * API routes
             */
            $router->middleware('api')->prefix('api')->group(function (Router $router) {
                $router->middleware('auth:api')->group(function (Router $router) {
                    $router->get('history', [ApiController::class, 'index'])->middleware('can:see history');
                    $router->delete('history', [ApiController::class, 'destroy'])->middleware('can:clear-history');
                });
            });
        });
    }
}
