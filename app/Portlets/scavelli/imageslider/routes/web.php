<?php

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::group(['prefix' => 'admin/imageslider', 'namespace' => 'App\Portlets\scavelli\imageslider\Controllers'], function() {
        Route::get('listmodels/{structure_id}', 'imageSliderController@listModels');
    });
});


Route::group(['middleware' => 'web','prefix' => 'web','namespace' => 'App\Portlets\scavelli\imageslider\Controllers'], function() {
    Route::get('{slug}', 'imageSliderController@viewFile');
});


