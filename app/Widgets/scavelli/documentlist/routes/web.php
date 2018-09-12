<?php

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::group(['prefix' => 'admin/documentlist', 'namespace' => 'App\Widgets\scavelli\documentlist\Controllers'], function() {
        Route::get('listmodels/{structure_id}', 'documentController@listModels');
    });
});


Route::group(['middleware' => 'web','prefix' => 'web','namespace' => 'App\Widgets\scavelli\documentlist\Controllers'], function() {
    Route::get('{slug}', 'documentController@viewFile');
});


