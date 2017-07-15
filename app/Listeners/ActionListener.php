<?php

namespace App\Listeners;

use App\Common\Utils;
use App\Events\ActionEvent;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ActionListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ActionEvent  $event
     * @return void
     */
    public function handle(ActionEvent $event)
    {
        $user = $event->getUser();
        $request = $event->getRequest();
        $now = Carbon::now()->toDateTimeString();

        $action['action'] = Utils::getPathUri($request);
        $action['params'] = encrypt(json_encode($request->all()));
        $action['operator_id'] = $user->id;
        $action['operator_name'] = $user->user_name;
        $action['created_at'] = $now;
        $action['updated_at'] = $now;

        DB::table('action_log')->insert($action);
    }
}
