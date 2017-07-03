<?php

namespace App\Http\ViewCreators;

use App\Logic\UserLogic;
use Illuminate\View\View;
use App\Repositories\UserRepository;

class AgentWidgetCreator
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
    public function create(View $view)
    {
        // 获取城市信息
        $user_logic = new UserLogic();
        $cities     = $user_logic->getOpenCities();
        $view->with('cities', $cities);
    }
}