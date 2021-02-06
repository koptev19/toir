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

Auth::routes();

Route::get('', 'IndexController@index')->name('index');
Route::get('home', 'IndexController@home')->name('home');

Route::get('profile/logout', 'ProfileController@logout')->name('profile.logout');

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('main', 'MainController@index')->name('main');
    Route::get('toir/{workshop}', 'ToirController@index')->name('toir');

    Route::get('settings', 'SettingController@index')->name('settings.index');
    Route::put('settings/update', 'SettingController@update')->name('settings.update');

    Route::get('equipments/children', 'EquipmentController@children')->name('equipments.children');

    Route::resources([
        'departments' => DepartmentController::class,
        'equipments' => EquipmentController::class,
        'users' => UserController::class,
        'accepts' => AcceptController::class,
    ]);
});

// Route::resource('equipments', EquipmentController::class);

// Route::resource('departments', DepartmentController::class);
