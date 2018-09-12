<?php

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::group(['prefix' => 'admin/contentlist', 'namespace' => 'App\Widgets\scavelli\contentlist\Controllers'], function() {
        Route::get('listmodels/{structure_id}', 'AssetController@listModels');
    });
});
