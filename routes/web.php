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
    return redirect()->intended('/login');
});

Route::get('/login', 'LoginController@showLoginForm')->name('login');
Route::post('/dologin', 'LoginController@login')->name('dologin');
Route::post('/logout', 'LoginController@logout')->name('logout');

Route::get('/404', function () {
    return view('errors.404');
});
Route::get('/500', function () {
    return view('errors.500');
});

Route::group(['middleware' => ['acl', 'validator']], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
    Route::get('/result', function () {
        return view('result');
    });
    Route::get('/user/agreement', function () {
        return view('agreement');
    });
    Route::post('/user/agree', 'UserController@agree')->name('user.agree');
    Route::get('/user/add', 'UserController@addUserForm')->name('user.add');
    Route::post('/user/doadd', 'UserController@add')->name('user.doadd');
    Route::get('/user/reset', 'UserController@addResetPasswordForm')->name('user.reset');
    Route::post('/user/doreset', 'UserController@reset')->name('user.doreset');

    Route::get('/recharge/agent', 'RechargeController@showAgentRechargeForm')->name('recharge.agent');
    Route::post('/recharge/doagent', 'RechargeController@agentRecharge')->name('recharge.doagent');
    Route::get('/recharge/user', 'RechargeController@showUserRechargeForm')->name('recharge.user');
    Route::post('/recharge/douser', 'RechargeController@userRecharge')->name('recharge.douser');

    Route::get('/agent/list', 'AgentController@agentList')->name('agent.list');
    Route::get('/agent/banlist', 'AgentController@banAgentList')->name('agent.banlist');
    Route::get('/agent/info', 'AgentController@agentInfo')->name('agent.info');
    Route::get('/agent/rechargelist', 'AgentController@rechargeList')->name('agent.rechargelist');
    Route::get('/agent/openroom', 'AgentController@showOpenRoomForm')->name('agent.openroom');
    Route::post('/agent/doopenroom', 'AgentController@openRoom')->name('agent.doopenroom');
    Route::get('/general_agent/list', 'FirstAgentController@agentList')->name('general_agent.list');
    Route::get('/general_agent/invite_code', 'FirstAgentController@inviteCode')->name('general_agent.invite_code');
    Route::get('/general_agent/banlist', 'FirstAgentController@banAgentList')->name('general_agent.banlist');
    Route::get('/general_agent/rechargelist', 'FirstAgentController@agentRechargeList')->name('general_agent.rechargelist');
    Route::get('/general_agent/cash_order_list', 'FirstAgentController@currentWeekCashOrderList')->name('general_agent.cash_order_list');

    Route::group(['prefix' => 'api'], function () {
        Route::get('/agent/ban', 'Api\AgentController@banAgent');
        Route::get('/agent/unban', 'Api\AgentController@unBanAgent');
        Route::get('/agent/info', 'Api\AgentController@agentInfo');
        Route::post('/agent/save', 'Api\AgentController@saveAgent');
        Route::get('/agent/reset', 'Api\AgentController@resetPassword');
        Route::get('/general_agent/info', 'Api\FirstAgentController@agentInfo');
        Route::post('/general_agent/save', 'Api\FirstAgentController@saveAgent');
        Route::get('/general_agent/ban', 'Api\FirstAgentController@banAgent');
        Route::get('/general_agent/unban', 'Api\FirstAgentController@unBanAgent');
        Route::get('/general_agent/delflow', 'Api\FirstAgentController@delAgentFlow');
        Route::get('/general_agent/do_cash_order', 'Api\FirstAgentController@confirmCashOrder');
    });

});
