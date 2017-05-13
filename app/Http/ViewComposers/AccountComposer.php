<?php

namespace App\Http\ViewComposers;

use App\Logic\AccountLogic;
use Illuminate\View\View;
use App\Repositories\UserRepository;

class AccountComposer
{
    /**
     * The user repository implementation.
     *
     * @var UserRepository
     */
    protected $users;

    /**
     * Create a new profile composer.
     *
     * @return void
     */
    public function __construct()
    {
        // Dependencies automatically resolved by service container...
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // 获取账户信息
        $account_logic = new AccountLogic();
        $accounts = $account_logic->getAgentBalance();
        $view->with('accounts', $accounts);
    }
}