<?php

namespace App\Http\Controllers;

use App\Common\Constants;
use App\Exceptions\Exception;
use App\Library\Protobuf\Command;
use App\Library\Protobuf\COMMAND_TYPE;
use App\Library\Protobuf\Protobuf;
use App\Library\TcpClient;
use App\Logic\AccountLogic;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$user_name = 'cloudbmwang';
        $roles = User::where('name', $user_name)->first()->roles[0]->toArray();*/
//        $roles = Auth::user()->roles()->first()->toArray();
//        var_dump($roles);
        // 注册服务器
        $inner_meta_register_srv = Protobuf::packRegisterInnerMeta();
        $register_res = TcpClient::callTcpService($inner_meta_register_srv);

        if ($register_res == $inner_meta_register_srv) {
            echo 'success';
        }
        $command['command_type'] = COMMAND_TYPE::COMMAND_TYPE_RECHARGE;
        $command['account'] = '998';
        $command['player_id'] = '14';
        $command['count'] = '100';
        $inner_meta_command = Protobuf::packCommandInnerMeta($command);
        $command_res = TcpClient::callTcpService($inner_meta_command);
        $command_res = Protobuf::unpackCommand($command_res);
        $command_res = Protobuf::unpackForResponse(TcpClient::callTcpService($inner_meta_command));

        var_dump($command_res);
        exit;
        /*$users = Role::where('id', Constants::ROLE_TYPE_AGENT)->first()->users()->get()->toArray();
        return view('agent.list', ['agents' => $users]);*/
    }

    public function initRole()
    {
        // Create Roles
        /*$founder = new Role();
        $founder->name = '超级管理员';
        $founder->save();
        $admin = new Role();
        $admin->name = '管理员';
        $admin->save();
        $agent = new Role();
        $agent->name = '代理';
        $agent->save();*/
        /*$founder = Role::where('name', '=', '超级管理员')->first();
        $admin = Role::where('name', '=', '管理员')->first();*/

        // Create User
        $user = User::where('name', '=', 'wbmcloud')->first();
        /*$user->attachRole($founder);
        $user->roles()->attach($founder->id);*/

        /*$createPost = new Permission();
        $createPost->name         = 'create-post';
        $createPost->display_name = 'Create Posts'; // optional
        $createPost->description  = 'create new blog posts'; // optional
        $createPost->save();

        $editUser = new Permission();
        $editUser->name         = 'edit-user';
        $editUser->display_name = 'Edit Users'; // optional
        $editUser->description  = 'edit existing users'; // optional
        $editUser->save();

        $admin->attachPermission($createPost);
        $founder->attachPermissions(array($createPost, $editUser));*/
        var_dump($user->hasRole('超级管理员'));  // false
        $user->hasRole('admin');   // true
        $user->can('edit-user');   // false
        $user->can('create-post'); // true
    }

}
