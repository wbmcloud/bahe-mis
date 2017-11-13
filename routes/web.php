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
Route::group(['middleware' => ['validator']], function () {
    Route::get('/', function () {
        return redirect()->intended('/login');
    });

    Route::get(\App\Common\ParamsRules::IF_USER_LOGIN, 'LoginController@showLoginForm')->name('login');
    Route::post(\App\Common\ParamsRules::IF_USER_DO_LOGIN, 'LoginController@login')->name('dologin');
    Route::post(\App\Common\ParamsRules::IF_USER_LOGOUT, 'LoginController@logout')->name('logout');

    Route::get(\App\Common\ParamsRules::IF_NOT_FOUND, function () {
        return view('errors.404');
    });
    Route::get(\App\Common\ParamsRules::IF_FATAL_ERROR, function () {
        return view('errors.500');
    });

    Route::group(['middleware' => ['acl']], function () {
        Route::get(\App\Common\ParamsRules::IF_DASHBOARD, 'IndexController@index');
        Route::get(\App\Common\ParamsRules::IF_PROMPT, 'PromptController@index');
        Route::get(\App\Common\ParamsRules::IF_USER_AGREEMENT, function () {
            return view('agreement');
        });
        Route::post(\App\Common\ParamsRules::IF_USER_AGREE, 'UserController@agree')->name('user.agree');
        Route::get(\App\Common\ParamsRules::IF_USER_ADD, 'UserController@addUserForm')->name('user.add');
        Route::post(\App\Common\ParamsRules::IF_USER_DO_ADD, 'UserController@add')->name('user.doadd');
        Route::get(\App\Common\ParamsRules::IF_USER_RESET, 'UserController@addResetPasswordForm')->name('user.reset');
        Route::post(\App\Common\ParamsRules::IF_USER_DO_RESET, 'UserController@reset')->name('user.doreset');

        Route::get(\App\Common\ParamsRules::IF_RECHARGE_AGENT, 'RechargeController@showAgentRechargeForm')->name('recharge.agent');
        Route::post(\App\Common\ParamsRules::IF_RECHARGE_DO_AGENT, 'RechargeController@agentRecharge')->name('recharge.doagent');
        Route::get(\App\Common\ParamsRules::IF_RECHARGE_USER, 'RechargeController@showUserRechargeForm')->name('recharge.user');
        Route::post(\App\Common\ParamsRules::IF_RECHARGE_DO_USER, 'RechargeController@userRecharge')->name('recharge.douser');

        Route::get(\App\Common\ParamsRules::IF_AGENT_LIST, 'AgentController@agentList')->name('agent.list');
        Route::get(\App\Common\ParamsRules::IF_AGENT_BAN_LIST, 'AgentController@banAgentList')->name('agent.banlist');
        Route::get(\App\Common\ParamsRules::IF_AGENT_INFO, 'AgentController@agentInfo')->name('agent.info');
        Route::get(\App\Common\ParamsRules::IF_AGENT_RECHARGE_LIST, 'AgentController@rechargeList')->name('agent.rechargelist');
        Route::get(\App\Common\ParamsRules::IF_AGENT_OPEN_ROOM, 'AgentController@showOpenRoomForm')->name('agent.openroom');
        Route::post(\App\Common\ParamsRules::IF_AGENT_DO_OPEN_ROOM, 'AgentController@openRoom')->name('agent.doopenroom');
        Route::get(\App\Common\ParamsRules::IF_FIRST_AGENT_LIST, 'FirstAgentController@agentList')->name('first_agent.list');
        Route::get(\App\Common\ParamsRules::IF_FIRST_AGENT_INVITE_CODE, 'FirstAgentController@inviteCode')->name('first_agent.invite_code');
        Route::get(\App\Common\ParamsRules::IF_FIRST_AGENT_BAN_LIST, 'FirstAgentController@banAgentList')->name('first_agent.banlist');
        Route::get(\App\Common\ParamsRules::IF_FIRST_AGENT_RECHARGE_LIST, 'FirstAgentController@agentRechargeList')->name('first_agent.rechargelist');
        Route::get(\App\Common\ParamsRules::IF_FIRST_AGENT_CASH_ORDER_LIST, 'FirstAgentController@lastWeekCashOrderList')->name('first_agent.cash_order_list');
        Route::get(\App\Common\ParamsRules::IF_FIRST_AGENT_INCOME, 'FirstAgentController@income')->name('first_agent.income');
        Route::get(\App\Common\ParamsRules::IF_FIRST_AGENT_SALE, 'FirstAgentController@sale')->name('first_agent.sale');
        Route::get(\App\Common\ParamsRules::IF_FIRST_AGENT_INCOME_HISTORY, 'FirstAgentController@incomeHistory')->name('first_agent.income_history');


        Route::get(\App\Common\ParamsRules::IF_GENERAL_AGENT_LIST, 'GeneralAgentController@agentList')->name('general_agent.list');
        Route::get(\App\Common\ParamsRules::IF_GENERAL_AGENT_INVITE_CODE, 'GeneralAgentController@inviteCode')->name('general_agent.invite_code');
        Route::get(\App\Common\ParamsRules::IF_GENERAL_AGENT_BAN_LIST, 'GeneralAgentController@banAgentList')->name('general_agent.banlist');
        Route::get(\App\Common\ParamsRules::IF_GENERAL_AGENT_RECHARGE_LIST, 'GeneralAgentController@agentRechargeList')->name('general_agent.rechargelist');
        Route::get(\App\Common\ParamsRules::IF_GENERAL_AGENT_FIRST_AGENT_RECHARGE_LIST, 'GeneralAgentController@firstAgentIncomeList')->name('general_agent.first_agent_rechargelist');
        Route::get(\App\Common\ParamsRules::IF_GENERAL_AGENT_CASH_ORDER_LIST, 'GeneralAgentController@lastWeekCashOrderList')->name('general_agent.cash_order_list');
        Route::get(\App\Common\ParamsRules::IF_GENERAL_AGENT_INCOME, 'GeneralAgentController@income')->name('general_agent.income');
        Route::get(\App\Common\ParamsRules::IF_GENERAL_AGENT_SALE, 'GeneralAgentController@sale')->name('general_agent.sale');
        Route::get(\App\Common\ParamsRules::IF_GENERAL_AGENT_INCOME_HISTORY, 'GeneralAgentController@incomeHistory')->name('general_agent.income_history');

        Route::get(\App\Common\ParamsRules::IF_GAME_PLAYER_LIST, 'GameController@playerList')->name('game.playerlist');

        Route::get(\App\Common\ParamsRules::IF_RECORD_AGENT_RECHARGE, 'RecordController@agentRecharge')->name('record.agentrecharge');
        Route::get(\App\Common\ParamsRules::IF_RECORD_USER_RECHARGE, 'RecordController@userRecharge')->name('record.userrecharge');
        Route::get(\App\Common\ParamsRules::IF_RECORD_OPEN_ROOM, 'RecordController@openRoom')->name('record.openroom');

        /**
         * ajax API
         */
        Route::get(\App\Common\ParamsRules::IF_API_AGENT_BAN, 'Api\AgentController@banAgent');
        Route::get(\App\Common\ParamsRules::IF_API_AGENT_UNBAN, 'Api\AgentController@unBanAgent');
        Route::get(\App\Common\ParamsRules::IF_API_AGENT_INFO, 'Api\AgentController@agentInfo');
        Route::post(\App\Common\ParamsRules::IF_API_AGENT_SAVE, 'Api\AgentController@saveAgent');
        Route::get(\App\Common\ParamsRules::IF_API_AGENT_RESET, 'Api\AgentController@resetPassword');

        Route::get(\App\Common\ParamsRules::IF_API_FIRST_AGENT_INFO, 'Api\FirstAgentController@agentInfo');
        Route::post(\App\Common\ParamsRules::IF_API_FIRST_AGENT_SAVE, 'Api\FirstAgentController@saveAgent');
        Route::get(\App\Common\ParamsRules::IF_API_FIRST_AGENT_BAN, 'Api\FirstAgentController@banAgent');
        Route::get(\App\Common\ParamsRules::IF_API_FIRST_AGENT_UNBAN, 'Api\FirstAgentController@unBanAgent');
        Route::get(\App\Common\ParamsRules::IF_API_FIRST_AGENT_DEL_FLOW, 'Api\FirstAgentController@delAgentFlow');
        Route::get(\App\Common\ParamsRules::IF_API_FIRST_AGENT_DO_CASH_ORDER, 'Api\FirstAgentController@confirmCashOrder');
        Route::get(\App\Common\ParamsRules::IF_API_FIRST_AGENT_RESET, 'Api\FirstAgentController@resetPassword');

        Route::get(\App\Common\ParamsRules::IF_API_GENERAL_AGENT_INFO, 'Api\GeneralAgentController@agentInfo');
        Route::post(\App\Common\ParamsRules::IF_API_GENERAL_AGENT_SAVE, 'Api\GeneralAgentController@saveAgent');
        Route::get(\App\Common\ParamsRules::IF_API_GENERAL_AGENT_BAN, 'Api\GeneralAgentController@banAgent');
        Route::get(\App\Common\ParamsRules::IF_API_GENERAL_AGENT_UNBAN, 'Api\GeneralAgentController@unBanAgent');
        Route::get(\App\Common\ParamsRules::IF_API_GENERAL_AGENT_DEL_FLOW, 'Api\GeneralAgentController@delAgentFlow');
        Route::get(\App\Common\ParamsRules::IF_API_GENERAL_AGENT_DO_CASH_ORDER, 'Api\GeneralAgentController@confirmCashOrder');
        Route::get(\App\Common\ParamsRules::IF_API_GENERAL_AGENT_RESET, 'Api\GeneralAgentController@resetPassword');
        Route::get(\App\Common\ParamsRules::IF_API_STAT_AGENT, 'Api\StatController@agent');
        Route::get(\App\Common\ParamsRules::IF_API_STAT_FLOW, 'Api\StatController@flow');
        Route::get(\App\Common\ParamsRules::IF_API_STAT_ROUNDS, 'Api\StatController@rounds');
        Route::get(\App\Common\ParamsRules::IF_API_STAT_DAU, 'Api\StatController@dau');
        Route::get(\App\Common\ParamsRules::IF_STAT_AGENT, 'StatController@agent')->name('stat.agent');
        Route::get(\App\Common\ParamsRules::IF_STAT_FLOW, 'StatController@flow')->name('stat.flow');
        Route::get(\App\Common\ParamsRules::IF_STAT_ROUNDS, 'StatController@rounds')->name('stat.rounds');
        Route::get(\App\Common\ParamsRules::IF_STAT_DAU, 'StatController@dau')->name('stat.dau');
    });
});
