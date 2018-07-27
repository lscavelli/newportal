<?php

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::group(['prefix' => 'admin/imageviewer', 'namespace' => 'App\Widgets\scavelli\imageviewer\Controllers'], function() {
        Route::get('listmodels/{structure_id}', 'imageController@listModels');
    });
});


Route::group(['middleware' => 'web','prefix' => 'web','namespace' => 'App\Widgets\scavelli\imageviewer\Controllers'], function() {
    Route::get('{slug}', 'imageController@viewFile');
});


