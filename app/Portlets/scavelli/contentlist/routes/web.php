<?php

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::group(['prefix' => 'admin/contentlist', 'namespace' => 'App\Portlets\scavelli\contentlist\Controllers'], function() {
        Route::get('listmodels/{structure_id}', 'AssetController@listModels');
    });
});
