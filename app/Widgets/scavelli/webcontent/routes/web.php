<?php

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::group(['prefix' => 'admin/webcontent', 'namespace' => 'App\Widgets\scavelli\webcontent\Controllers'], function() {

        Route::get('listmodels/{content_id}', 'ContentWebController@listModels');
        Route::post('listcontent', 'ContentWebController@listContent');

    });
});
