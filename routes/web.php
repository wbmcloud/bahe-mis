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


Route::group(['middleware' => ['acl', 'validator']], function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    });
    Route::get('/user/agreement', function () {
        return view('/agreement');
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
    Route::get('/general_agent/list', 'GeneralAgentController@agentList')->name('general_agent.list');
    Route::get('/general_agent/add', 'GeneralAgentController@addAgentForm')->name('general_agent.add');
    Route::post('/general_agent/doadd', 'GeneralAgentController@addAgent')->name('general_agent.doadd');
    Route::get('/general_agent/invite_code', 'GeneralAgentController@inviteCode')->name('general_agent.invite_code');
    Route::get('/general_agent/banlist', 'GeneralAgentController@banAgentList')->name('general_agent.banlist');
    Route::get('/general_agent/rechargelist', 'GeneralAgentController@agentRechargeList')->name('general_agent.rechargelist');

    Route::group(['prefix' => 'api'], function () {
        Route::get('/agent/ban', 'Api\AgentController@banAgent');
        Route::get('/agent/unban', 'Api\AgentController@unBanAgent');
        Route::get('/agent/add', 'Api\AgentController@addAgent');
        Route::get('/agent/info', 'Api\AgentController@agentInfo');
        Route::post('/agent/save', 'Api\AgentController@saveAgent');
        Route::get('/agent/reset', 'Api\AgentController@resetPassword');
        Route::get('/general_agent/info', 'Api\GeneralAgentController@agentInfo');
        Route::post('/general_agent/save', 'Api\GeneralAgentController@saveAgent');
        Route::get('/general_agent/ban', 'Api\GeneralAgentController@banAgent');
        Route::get('/general_agent/unban', 'Api\GeneralAgentController@unBanAgent');
        Route::get('/general_agent/delflow', 'Api\GeneralAgentController@delAgentFlow');
    });

});
