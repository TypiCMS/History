<?php

namespace TypiCMS\Modules\History\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use TypiCMS\Modules\History\Http\Controllers\ApiController;

class RouteServiceProvider extends ServiceProvider
{
    public function map(): void
    {
        /*
         * API routes
         */
        Route::middleware(['api', 'auth:api'])->prefix('api')->group(function (Router $router) {
            $router->get('history', [ApiController::class, 'index'])->middleware('can:see history');
            $router->delete('history', [ApiController::class, 'destroy'])->middleware('can:clear history');
        });
    }
}
