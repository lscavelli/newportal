<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect("/welcome");
});


Route::group(['prefix'=>'admin','middleware' => ['web', 'auth']], function () {

    Route::get('api/listportlets', "Content\\PortletController@listPortletDisp");
    Route::any('api/saveportlets', "PageController@savePortlets");
    Route::any('api/listcatecory/{vocabulary_id}/{default?}', "ContentController@listCategoryJson");

    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('dashboard/data', 'DashboardController@activitiesData');

    Route::any('content/store', 'ContentController@store');
    Route::any('content/{structure_id?}', 'ContentController@index')->name('content');
    Route::get('content/create/{structure_id?}', 'ContentController@create');
    Route::get('content/{content_id}/edit/', 'ContentController@edit');
    Route::get('content/{structure_id}/edit/{content_id}', 'ContentController@editWrapper');
    Route::post('content/update/{content_id}', 'ContentController@update');
    Route::delete('content/{content_id}', 'ContentController@destroy');
    Route::get('content/categorization/{content_id}', 'ContentController@categorization');
    Route::get('content/model/{content_id}', 'ContentController@model');
    Route::get('content/estratto/{content_id}', 'ContentController@extract');
    Route::post('content/otherupdate/{content_id}', 'ContentController@otherUpdate');

    Route::resource('structure','StructureController');

    Route::group(['namespace' => 'Blog'], function () {
        Route::resource('posts','PostController');
    });

    Route::group(['namespace' => 'Content'], function () {

        Route::get('comments/{service}/{post_id?}', 'CommentController@index')->name('comments');
        Route::get('comments/{service}/{post_id}/create', 'CommentController@create');
        Route::post('comments/store', 'CommentController@store');
        Route::get('comments/{service}/{post_id}/edit/{comment_id}', 'CommentController@edit');
        Route::post('comments/update/{comment_id}', 'CommentController@update');
        Route::post('comments/{service}/{post_id}/delete/{comment_id}', 'CommentController@destroy');

        Route::resource('portlets','PortletController');

        Route::resource('ddl','DynamicDataController');
        Route::any('ddl/structure/{q?}', 'DynamicDataController@structureRemoteData');

        Route::get('ddl/content/{ddl_id}/create', 'DynamicContentController@create');
        Route::any('ddl/content/store', 'DynamicContentController@store');
        // spostato sotto per store
        Route::any('ddl/content/{ddl_id}', 'DynamicContentController@index')->where('ddl_id', '[0-9]+')->name('ddlcontent');
        Route::get('ddl/content/{ddl_id}/{content_id}/edit/', 'DynamicContentController@edit');
        Route::post('ddl/content/update/{content_id}', 'DynamicContentController@update');
        Route::delete('ddl/content/{ddl_id}/{content_id}', 'DynamicContentController@destroy');

        Route::resource('tags','TagController');

        Route::post('categories/store', 'CategoryController@store');
        // spostato sotto per store
        Route::any('categories/{vocabulary_id?}', 'CategoryController@index')->name('categories');
        Route::get('categories/create/{vocabulary_id}', 'CategoryController@create');
        Route::get('categories/{vocabulary_id}/create', 'CategoryController@create');
        Route::get('categories/{category_id}/edit', 'CategoryController@edit');
        Route::post('categories/update/{category_id}', 'CategoryController@update');
        Route::delete('categories/{category_id}', 'CategoryController@destroy');

        Route::any('categories/assignSubcat/{category_id}', 'CategoryController@assignSubcat');
        Route::get('categories/{category_id}/addSubcat/{subcat_id}', 'CategoryController@addSubcat');
        Route::get('categories/removeSubcat/{subcat_id}', 'CategoryController@delSubcat');

        Route::get('categories/profile/{category_id}', 'CategoryController@profile');
        Route::get('categories/treeview', 'CategoryController@treeViewOrg');

        Route::resource('vocabularies','VocabularyController');

        Route::get('models/{structure_id}', 'ModelliController@index')->name('models');
        Route::get('models/{structure_id}/create', 'ModelliController@create');
        Route::get('models/create/{structure_id}', 'ModelliController@create'); // alternativo per menu contestuale
        Route::any('models/store', 'ModelliController@store');
        Route::delete('models/{structure_id}/{model_id}', 'ModelliController@destroy');
        Route::get('models/{structure_id}/{model_id}/edit', 'ModelliController@edit');
        Route::post('models/update/{model_id}', 'ModelliController@update');
        Route::get('models/duplicates/{model_id}', 'ModelliController@duplicates');

    });

    Route::get('pages/create/{page_id?}','PageController@create');
    Route::resource('pages','PageController');

    Route::get('pages/removeChild/{child_id}', 'PageController@delChild');
    Route::get('pages/addLayout/{page_id}/{frame_id?}', 'PageController@layout');
    Route::get('pages/{page_id}/addPortlet/{portlet_id}/{frame}', 'PageController@addPortlet');
    Route::get('pages/{page_id}/removePivotId/{pivot_id}', 'PageController@delPortlet');
    Route::any('pages/{page_id}/configPortlet/{pivot_id}', 'PageController@configPortlet');
    Route::post('pages/savepref', 'PageController@savePref');
    Route::get('pages/getpref/{pivot_id}', 'PageController@getPref');
    Route::get('pages/test/{id}/{pos}/{newpos}', 'PageController@test');
    Route::get('pages/duplicates/{page_id}', 'PageController@duplicates');

    Route::get('test', function () {
        //use App\Repositories\Repository;
        //$repo = new Repository(new \App\Models\User);
        //return $repo->countByStatus(1);
        //print "<pre>";
        //print_r($data);
        //exit;

        //$role = \App\Models\Role::find(3);
        //$perm = new \App\Http\Controllers\PermissionController();
        //$role = new \App\Http\Controllers\RoleController();
        //dd($role->hasAllPerm(['view_post','update_post','hjgfk']));
        //$role->addPerm(3,[1]);
        //$perm->delPermission(3,[3]);
        //dd($perm->listRoles(2));
        //$role->delPerm(3,[3,4]);
        //return $perm->countPerm(3);
        //$role->test();
    });

    /**
     * Accessibile a tutti gli utenti anche senza particolari permessi
     */
    Route::get('users/revert', 'UserController@revertUser');
    Route::any('users/cities/{q?}', 'UserController@citiesRemoteData');

    /**
     * Per accedere alla propria scheda
     */
    Route::group(['middleware' => ['can:profile,user_id']], function() {
        Route::get('users/{user_id}', 'UserController@profile')->name('profile')->where('user_id', '[0-9]+');
        Route::post('users/{user_id}/edit/avatar', 'UserController@setAvatar');
        Route::get('users/{user_id}/edit', 'UserController@edit');
        Route::post('users/update/{user_id}', 'UserController@update');
    });

    /**
     * Accessibile all'utente "Super Admin" e agli utenti possessori
     * del permesso users-manage
     */
    Route::group(['middleware' => 'can:users-manage'], function() {

        // Utenti
        // *****************************************************************************
        Route::get('users', 'UserController@index')->name('users');
        Route::post('users', 'UserController@index');
        Route::get('users/impersonate/{user_id}', 'UserController@impersonateUser');
        Route::any('users/activity/{user_id?}', 'UserController@showActivity');
        Route::any('users/sessions/{user_id?}', 'UserController@showSessions');
        Route::any('users/sessions/delete/{session_id}', 'UserController@sessionDestroy');
        Route::any('users/profile/{user_id}/delete/{session_id}', 'UserController@sessionDestroy');
        Route::get('users/create', 'UserController@create');
        Route::post('users/store', 'UserController@store');
        Route::delete('users/{user_id}', 'UserController@destroy');

        Route::any('users/assignPerm/{user_id}', 'UserController@assignPerm');
        Route::get('users/{user_id}/addPermission/{permission_id}', 'UserController@addPerm');
        Route::get('users/{user_id}/removePermission/{permission_id}', 'UserController@delPerm');

        Route::any('users/assignRole/{user_id}', 'UserController@assignRole');
        Route::get('users/{user_id}/addRole/{role_id}', 'UserController@addRole');
        Route::get('users/{user_id}/removeRole/{role_id}', 'UserController@delRole');

        Route::post('users/addRole', 'UserController@addRole');
        Route::post('users/addPermission', 'UserController@addPermission');
        Route::get('users/removePermission/{permission}/{user_id}', 'UserController@revokePermission');
        Route::get('users/removeRole/{role}/{user_id}', 'UserController@revokeRole');

        // Ruoli
        // *****************************************************************************
        Route::resource('roles','RoleController');

        Route::any('roles/assign/{role_id}', 'RoleController@assignPerm');
        Route::get('roles/{role_id}/addPermission/{permission_id}', 'RoleController@addPerm');
        Route::get('roles/{role_id}/removePermission/{permission_id}', 'RoleController@delPerm');

        // Permessi
        // *****************************************************************************
        Route::resource('permissions','PermissionController');

        // Gruppi di utenti
        // *****************************************************************************
        Route::resource('groups','GroupController');

        Route::any('groups/assign/{group_id}', 'GroupController@assignUser');
        Route::get('groups/{group_id}/addUser/{user_id}', 'GroupController@addUser');
        Route::get('groups/{group_id}/removeUser/{user_id}', 'GroupController@delUser');
        Route::get('groups/{group_id}/removeAllUser', 'GroupController@delAllUsers');

        Route::any('groups/assignPerm/{group_id}', 'GroupController@assignPerm');
        Route::get('groups/{group_id}/addPermission/{permission_id}', 'GroupController@addPerm');
        Route::get('groups/{group_id}/removePermission/{permission_id}', 'GroupController@delPerm');

        Route::any('groups/assignRole/{group_id}', 'GroupController@assignRole');
        Route::get('groups/{group_id}/addRole/{role_id}', 'GroupController@addRole');
        Route::get('groups/{group_id}/removeRole/{role_id}', 'GroupController@delRole');

        // Organizzazioni
        // *****************************************************************************
        Route::resource('organizations','OrganizationController');

        Route::any('organizations/assignFilial/{organization_id}', 'OrganizationController@assignFilial');
        Route::get('organizations/{organization_id}/addFilial/{filial_id}', 'OrganizationController@addFilial');
        Route::get('organizations/removeFilial/{filial_id}', 'OrganizationController@delFilial');
        Route::any('organizations/assignUser/{organization_id}', 'OrganizationController@assignUser');
        Route::get('organizations/{organization_id}/addUser/{user_id}', 'OrganizationController@addUser');
        Route::get('organizations/{organization_id}/removeUser/{user_id}', 'OrganizationController@delUser');

        Route::get('organizations/treeview', 'OrganizationController@treeViewOrg');

        Route::group(['namespace' => 'General'], function () {
            Route::get('settings','SettingController@index')->name('settings');
            Route::post('settings/storeorupdate','SettingController@storeOrUpdate');
        });
    });

});

Auth::routes();
Route::get('login/{provider}', 'Auth\SocialController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\SocialController@getProviderCallback');

Route::post('contactform', 'Mail\\MailController@contact');
Route::get('sitemap.xml', 'SiteMapController@siteMap');
Route::get('users/confirmation/{token}', 'Auth\\RegisterController@confirmationEmail')->name('confirmation.email');

Route::match(['get', 'post'],'{uri}','PublicPageController@getPage')->where('uri', '((?!admin).*)?'); //'([A-z\d-\/_.]+)?');
//Route::get('{uri?}','PublicPageController@getPage');