<?php

namespace App\Http\Controllers;

use App\Common\Constants;
use App\Logic\UserBindLogic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class UserBindController extends Controller
{
    /**
     * 我的用户绑定记录
     */
    public function myUserBindRecord()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;
        return [
            'records' => (new UserBindLogic())->getMyUserBindList($page_size, $this->params)
        ];
    }

    /**
     * 旗下用户绑定记录
     */
    public function subUserBindRecord()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;
        return [
            'records' => (new UserBindLogic())->getSubUserBindList($page_size, $this->params)
        ];
    }

    /**
     * 代充值记录
     */
    public function replaceRechargeRecord()
    {
        $page_size  = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;
        $start_time = isset($this->params['start_date']) ? $this->params['start_date'] :
            Carbon::today()->toDateString();
        $end_time   = isset($this->params['end_date']) ? $this->params['end_date'] :
            Carbon::tomorrow()->toDateString();

        return [
            'recharge_flows' => (new UserBindLogic())->getReplaceRechargeRecord(Auth::id(), $start_time, $end_time, $page_size)
        ];
    }
}
