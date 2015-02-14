<?php
Route::group(['prefix'=>'api'], function() {
    Route::resource(
        'history',
        'TypiCMS\Modules\History\Http\Controllers\ApiController',
        ['only' => ['index']]
    );
});
