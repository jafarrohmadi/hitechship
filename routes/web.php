<?php
Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }
    return redirect()->route('admin.home');
}
);
Auth::routes(['register' => false]);
Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/getInfoVersion', 'HomeController@getInfoVersion');
    Route::get('/getInfoErrors', 'HomeController@getInfoErrors');
    Route::get('/getInfoUtcTime', 'HomeController@getInfoUtcTime');
    Route::get('/getSubAccountInfos', 'HomeController@getSubAccountInfos');
    Route::get('/getBroadcastInfos', 'HomeController@getBroadcastInfos');
    Route::get('/getMobilesPaged', 'HomeController@getMobilesPaged');
    Route::get('/getReturnMessages', 'HomeController@getReturnMessages');
    Route::get('/getForwardStatus', 'HomeController@getForwardStatus');
    Route::get('/getForwardMessages', 'HomeController@getForwardMessages');
    Route::get('/submitMessages', 'HomeController@submitMessages');
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');
    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');
    // Users
    Route::get('change-password', 'UsersController@changePassword')->name('change-password');
    Route::post('store-password', 'UsersController@storePassword')->name('store-password');
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');
    // Managers
    Route::delete('managers/destroy', 'ManagerController@massDestroy')->name('managers.massDestroy');
    Route::resource('managers', 'ManagerController');
    // Ships
    Route::delete('ships/destroy', 'ShipController@massDestroy')->name('ships.massDestroy');
    Route::resource('ships', 'ShipController');
    // Terminals
    Route::delete('terminals/destroy', 'TerminalController@massDestroy')->name('terminals.massDestroy');
    Route::resource('terminals', 'TerminalController');
    // History Ships
    Route::delete('history-ships/destroy', 'HistoryShipController@massDestroy')->name('history-ships.massDestroy');
    Route::resource('history-ships', 'HistoryShipController');

    Route::get('global-search', 'GlobalSearchController@search')->name('globalSearch');
}
);
