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
    // spostato sotto per store
    Route::any('content/{structure_id?}', 'ContentController@index')->name('content');
    Route::get('content/create/{structure_id?}', 'ContentController@create');
    Route::get('content/edit/{content_id}', 'ContentController@edit');
    Route::get('content/{structure_id}/edit/{content_id}', 'ContentController@editWrapper');
    Route::post('content/update/{content_id}', 'ContentController@update');
    Route::post('content/delete/{content_id}', 'ContentController@destroy');
    Route::get('content/categorization/{content_id}', 'ContentController@categorization');
    Route::get('content/model/{content_id}', 'ContentController@model');
    Route::get('content/estratto/{content_id}', 'ContentController@extract');
    Route::post('content/otherupdate/{content_id}', 'ContentController@otherUpdate');

    Route::any('structure', 'StructureController@index')->name('structure');
    Route::get('structure/create', 'StructureController@create');
    Route::any('structure/store', 'StructureController@store');
    Route::get('structure/edit/{structure_id}', 'StructureController@edit');
    Route::post('structure/update/{structure_id}', 'StructureController@update');
    Route::post('structure/delete/{structure_id}', 'StructureController@destroy');


    Route::group(['namespace' => 'Blog'], function () {

        Route::any('posts', 'PostController@index')->name('posts');
        Route::get('posts/create', 'PostController@create');
        Route::post('posts/store', 'PostController@store');
        Route::get('posts/edit/{post_id}', 'PostController@edit');
        Route::post('posts/update/{post_id}', 'PostController@update');
        Route::post('posts/delete/{post_id}', 'PostController@destroy');

        Route::get('comments/{post_id}/create/', 'CommentController@create');
        Route::get('comments/create/{post_id}', 'CommentController@create');
        Route::post('comments/store', 'CommentController@store');
        // spostato sotto per store
        Route::any('comments/{post_id?}', 'CommentController@index')->name('comments');
        Route::get('comments/{post_id}/edit/{comment_id}', 'CommentController@edit');
        Route::post('comments/update/{comment_id}', 'CommentController@update');
        Route::post('comments/{post_id}/delete/{comment_id}', 'CommentController@destroy');

    });

    Route::group(['namespace' => 'Content'], function () {

        Route::get('portlets', 'PortletController@index')->name('portlets');
        Route::post('portlets/store', 'PortletController@store');
        Route::post('portlets/delete/{portlet_id}', 'PortletController@destroy');

        Route::any('ddl', 'DynamicDataController@index')->name('ddl');
        Route::get('ddl/create', 'DynamicDataController@create');
        Route::any('ddl/store', 'DynamicDataController@store');
        Route::get('ddl/edit/{ddl_id}', 'DynamicDataController@edit');
        Route::post('ddl/update/{ddl_id}', 'DynamicDataController@update');
        Route::post('ddl/delete/{ddl_id}', 'DynamicDataController@destroy');
        Route::any('ddl/structure/{q?}', 'DynamicDataController@structureRemoteData');

        Route::get('ddl/content/{ddl_id}/create', 'DynamicContentController@create');
        Route::any('ddl/content/store', 'DynamicContentController@store');
        // spostato sotto per store
        Route::any('ddl/content/{ddl_id}', 'DynamicContentController@index')->where('ddl_id', '[0-9]+')->name('ddlcontent');
        Route::get('ddl/content/{ddl_id}/edit/{content_id}', 'DynamicContentController@edit');
        Route::post('ddl/content/update/{content_id}', 'DynamicContentController@update');
        Route::post('ddl/content/{ddl_id}/delete/{content_id}', 'DynamicContentController@destroy');

        Route::get('tags', 'TagController@index')->name('tags');
        Route::get('tags/create', 'TagController@create');
        Route::post('tags/store', 'TagController@store');
        Route::get('tags/edit/{tag_id}', 'TagController@edit');
        Route::post('tags/update/{tag_id}', 'TagController@update');
        Route::get('tags/content/{tag_id}', 'TagController@content');
        Route::post('tags/delete/{tag_id}', 'TagController@destroy');

        Route::post('categories/store', 'CategoryController@store');
        // spostato sotto per store
        Route::any('categories/{vocabulary_id?}', 'CategoryController@index')->name('categories');
        Route::get('categories/create/{vocabulary_id}', 'CategoryController@create');
        Route::get('categories/{vocabulary_id}/create', 'CategoryController@create');
        Route::get('categories/edit/{category_id}', 'CategoryController@edit');
        Route::post('categories/update/{category_id}', 'CategoryController@update');
        Route::post('categories/delete/{category_id}', 'CategoryController@destroy');

        Route::any('categories/assignSubcat/{category_id}', 'CategoryController@assignSubcat');
        Route::get('categories/{category_id}/addSubcat/{subcat_id}', 'CategoryController@addSubcat');
        Route::get('categories/removeSubcat/{subcat_id}', 'CategoryController@delSubcat');

        Route::get('categories/profile/{category_id}', 'CategoryController@profile');
        Route::get('categories/treeview', 'CategoryController@treeViewOrg');

        Route::any('vocabularies', 'VocabularyController@index')->name('vocabularies');
        Route::get('vocabularies/create', 'VocabularyController@create');
        Route::post('vocabularies/store', 'VocabularyController@store');
        Route::get('vocabularies/edit/{vocabulary_id}', 'VocabularyController@edit');
        Route::post('vocabularies/update/{vocabulary_id}', 'VocabularyController@update');
        Route::post('vocabularies/delete/{vocabular_id}', 'VocabularyController@destroy');

        Route::get('models/{structure_id}', 'ModelliController@index')->name('models');
        Route::get('models/{structure_id}/create', 'ModelliController@create');
        Route::get('models/create/{structure_id}', 'ModelliController@create'); // alternativo per menu contestuale
        Route::any('models/store', 'ModelliController@store');
        Route::post('models/{structure_id}/delete/{model_id}', 'ModelliController@destroy');
        Route::get('models/{structure_id}/edit/{model_id}', 'ModelliController@edit');
        Route::post('models/update/{model_id}', 'ModelliController@update');
        Route::get('models/duplicates/{model_id}', 'ModelliController@duplicates');

    });

    Route::get('pages', 'PageController@index')->name('pages');
    Route::post('pages', 'PageController@index');
    Route::get('pages/create/{page_id?}', 'PageController@create');
    Route::post('pages/store', 'PageController@store');
    Route::get('pages/edit/{page_id}', 'PageController@edit');
    Route::post('pages/update/{page_id}', 'PageController@update');
    Route::post('pages/delete/{page_id}', 'PageController@destroy');
    Route::get('pages/profile/{page_id}', 'PageController@profile');
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
     * Accessibile all'utente "Super Admin" e agli utenti possessori
     * del permesso users-manage
     */
    Route::group(['middleware' => 'can:users-manage'], function() {

        Route::get('users', 'UserController@index')->name('users');
        Route::post('users', 'UserController@index');
        Route::get('users/profile/{user_id}', 'UserController@profile');
        Route::any('users/cities/{q?}', 'UserController@citiesRemoteData');
        Route::any('users/activity/{user_id?}', 'UserController@showActivity');
        Route::any('users/sessions/{user_id?}', 'UserController@showSessions');
        Route::any('users/sessions/delete/{session_id}', 'UserController@sessionDestroy');
        Route::any('users/profile/{user_id}/delete/{session_id}', 'UserController@sessionDestroy');
        Route::post('users/edit/{user_id}/avatar', 'UserController@setAvatar');
        Route::get('users/edit/{user_id}', 'UserController@edit');
        Route::post('users/update/{user_id}', 'UserController@update');
        Route::get('users/create', 'UserController@create');
        Route::post('users/store', 'UserController@store');
        Route::post('users/delete/{user_id}', 'UserController@destroy');

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

        Route::get('roles', ['as'=> 'roles', 'uses'=> 'RoleController@index']);
        Route::post('roles', 'RoleController@index');
        Route::get('roles/create', 'RoleController@create');
        Route::post('roles/store', 'RoleController@store');
        Route::get('roles/edit/{role_id}', 'RoleController@edit');
        Route::post('roles/update/{role_id}', 'RoleController@update');
        Route::post('roles/delete/{role_id}', 'RoleController@destroy');

        Route::any('roles/assign/{role_id}', 'RoleController@assignPerm');
        Route::get('roles/{role_id}/addPermission/{permission_id}', 'RoleController@addPerm');
        Route::get('roles/{role_id}/removePermission/{permission_id}', 'RoleController@delPerm');

        Route::get('roles/profile/{role_id}', 'RoleController@profile');

        Route::get('permissions', ['as'=> 'permissions', 'uses'=> 'PermissionController@index']);
        Route::post('permissions', 'PermissionController@index');
        Route::get('permissions/create', 'PermissionController@create');
        Route::post('permissions/store', 'PermissionController@store');
        Route::get('permissions/edit/{permission_id}', 'PermissionController@edit');
        Route::post('permissions/update/{permission_id}', 'PermissionController@update');
        Route::post('permissions/delete/{permission_id}', 'PermissionController@destroy');

        Route::get('permissions/profile/{permission_id}', 'PermissionController@profile');

        Route::get('groups', ['as'=> 'groups', 'uses'=> 'GroupController@index']);
        Route::post('groups', 'GroupController@index');
        Route::get('groups/create', 'GroupController@create');
        Route::post('groups/store', 'GroupController@store');
        Route::get('groups/edit/{group_id}', 'GroupController@edit');
        Route::post('groups/update/{group_id}', 'GroupController@update');
        Route::post('groups/delete/{group_id}', 'GroupController@destroy');

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

        Route::get('groups/profile/{group_id}', 'GroupController@profile');

        Route::get('organizations', ['as'=> 'organizations', 'uses'=> 'OrganizationController@index']);
        Route::post('organizations', 'OrganizationController@index');
        Route::get('organizations/create', 'OrganizationController@create');
        Route::post('organizations/store', 'OrganizationController@store');
        Route::get('organizations/edit/{organization_id}', 'OrganizationController@edit');
        Route::post('organizations/update/{organization_id}', 'OrganizationController@update');
        Route::post('organizations/delete/{organization_id}', 'OrganizationController@destroy');

        Route::any('organizations/assignFilial/{organization_id}', 'OrganizationController@assignFilial');
        Route::get('organizations/{organization_id}/addFilial/{filial_id}', 'OrganizationController@addFilial');
        Route::get('organizations/removeFilial/{filial_id}', 'OrganizationController@delFilial');
        Route::any('organizations/assignUser/{organization_id}', 'OrganizationController@assignUser');
        Route::get('organizations/{organization_id}/addUser/{user_id}', 'OrganizationController@addUser');
        Route::get('organizations/{organization_id}/removeUser/{user_id}', 'OrganizationController@delUser');

        Route::get('organizations/profile/{organization_id}', 'OrganizationController@profile');
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
Route::get('{uri}','PublicPageController@getPage')->where('uri', '((?!admin).*)?'); //'([A-z\d-\/_.]+)?');
//Route::get('{uri?}','PublicPageController@getPage');