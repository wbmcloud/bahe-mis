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
Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['acl']], function () {
    Route::get('/', function () {
        return redirect()->intended('/dashboard');
    });

    Route::get('dashboard', function () {
        return view('dashboard');
    });

    Route::get('/user/add', 'UserController@addUserForm');
    Route::post('/user/add', 'UserController@add')->name('user.add');

    Route::get('/user/reset', 'UserController@addResetPasswordForm');
    Route::post('/user/reset', 'UserController@reset')->name('user.reset');

    Route::get('/recharge/agent', 'RechargeController@showAgentRechargeForm');
    Route::post('/recharge/agent', 'RechargeController@agentRecharge')->name('recharge.agent');

    Route::get('/recharge/user', 'RechargeController@showUserRechargeForm');
    Route::post('/recharge/user', 'RechargeController@userRecharge')->name('recharge.user');

});

Auth::routes();
