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
Route::get('/', function () {
    return redirect()->intended('/login');
});

Route::group(['middleware' => ['acl', 'validator']], function () {
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

    Route::get('/agent/list', 'AgentController@agentList')->name('agent.list');
    Route::get('/agent/banlist', 'AgentController@banAgentList')->name('agent.banlist');
    Route::get('/agent/info', 'AgentController@agentInfo')->name('agent.info');
    Route::get('/general_agent/list', 'GeneralAgentController@agentList')->name('general_agent.list');
    Route::get('/general_agent/add', 'GeneralAgentController@addAgentForm');
    Route::post('/general_agent/add', 'GeneralAgentController@addAgent')->name('general_agent.add');
    Route::get('/general_agent/invite_code', 'GeneralAgentController@inviteCode')->name('general_agent.invite_code');
    Route::get('/general_agent/banlist', 'GeneralAgentController@banAgentList')->name('general_agent.banlist');

    Route::group(['prefix' => 'api'], function () {
        Route::get('/agent/ban', 'Api\AgentController@banAgent');
        Route::get('/agent/unban', 'Api\AgentController@unBanAgent');
        Route::get('/agent/add', 'Api\AgentController@addAgent');
        Route::get('/agent/info', 'Api\AgentController@agentInfo');
        Route::post('/agent/save', 'Api\AgentController@saveAgent');
        Route::get('/agent/list', 'Api\AgentController@agentList');
        Route::get('/general_agent/info', 'Api\GeneralAgentController@agentInfo');
        Route::post('/general_agent/save', 'Api\GeneralAgentController@saveAgent');
        Route::get('/general_agent/ban', 'Api\GeneralAgentController@banAgent');
        Route::get('/general_agent/unban', 'Api\GeneralAgentController@unBanAgent');
    });

});


Auth::routes();