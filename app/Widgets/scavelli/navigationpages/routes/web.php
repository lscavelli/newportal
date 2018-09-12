<?php

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::group(['prefix' => 'admin/navigationpages', 'namespace' => 'App\Widgets\scavelli\navigationpages\Controllers'], function() {
        Route::get('listlayout/{theme}', 'pageController@listLayout');
    });
});
