<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/10
 * Time: 下午10:45
 */
namespace App\Http\Controllers;

use App\Common\Constants;
use App\Exceptions\SlException;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{

    public function agentList()
    {
        // $page = isset($this->params['page']) ? $this->params['page'] : Constants::DEFAULT_PAGE;
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] : Constants::DEFAULT_PAGE_SIZE;

        if (isset($this->params['query_str']) && !empty($this->params['query_str'])) {
            $users = Role::where('id', Constants::ROLE_TYPE_AGENT)
                ->first()
                ->users()
                ->where('status', Constants::COMMON_ENABLE)
                ->where('name', $this->params['query_str'])
                ->paginate($page_size);
        } else {
            $users = Role::where('id', Constants::ROLE_TYPE_AGENT)
                ->first()
                ->users()
                ->where('status', Constants::COMMON_ENABLE)
                ->paginate($page_size);
        }
        return view('agent.list', [
            'agents' => $users,
        ]);
            // ->forPage($page, $page_size)
            // ->get()
            // ->toArray();
        /*$users = array_map(function($user) {
            return ['id' => $user['id'],
                'name' => $user['name'],
                'created_at' => $user['created_at']];
        }, $users);
        $total_count = Role::where('id', Constants::ROLE_TYPE_AGENT)
            ->first()
            ->users()
            ->where('status', Constants::COMMON_ENABLE)
            ->count();

        return view('agent.list', [
            'agents' => $users,
            'total_count' => $total_count,
        ]);*/
    }

    public function banAgentList()
    {
        $page_size = isset($this->params['page_size']) ? $this->params['page_size'] :
            Constants::DEFAULT_PAGE_SIZE;

        if (isset($this->params['query_str']) && !empty($this->params['query_str'])) {
            $users = Role::where('id', Constants::ROLE_TYPE_AGENT)
                ->first()
                ->users()
                ->where('status', Constants::COMMON_DISABLE)
                ->where('name', $this->params['query_str'])
                ->paginate($page_size);
        } else {
            $users = Role::where('id', Constants::ROLE_TYPE_AGENT)
                ->first()
                ->users()
                ->where('status', Constants::COMMON_DISABLE)
                ->paginate($page_size);
        }
        return view('agent.banlist', [
            'agents' => $users,
        ]);
    }

    public function agentInfo()
    {
        $user = User::find($this->params['id']);
        if (empty($user)) {
            throw new SlException(SlException::AGENT_NOT_EXSIST_CODE);
        }
        return view('agent.info', [
            'agent_info' => $user
        ]);
    }

}