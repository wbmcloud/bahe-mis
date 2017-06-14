<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/10
 * Time: 下午10:45
 */
namespace App\Http\Controllers;

use App\Logic\UserLogic;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function addUserForm()
    {
        $cities = (new UserLogic())->getAllOpenCities();
        return [
            'cities' => $cities
        ];
    }

    public function addResetPasswordForm()
    {
        return [];
    }

    public function add(Request $request)
    {
        // 获取角色
        $role = Role::where('name', $this->params['role_name'])->first();
        if (empty($role)) {
            return false;
        }

        // 创建用户
        $user = new User();
        $user->name = $this->params['name'];
        $user->password = bcrypt($this->params['password']);
        $user->city_id = $this->params['city_id'];
        !empty($this->params['invite_code']) && ($user->invite_code = $this->params['invite_code']);
        !empty($this->params['uin']) && ($user->uin = $this->params['uin']);
        !empty($this->params['wechat']) && ($user->wechat = $this->params['wechat']);
        !empty($this->params['uin_group']) && ($user->uin_group = $this->params['uin_group']);
        !empty($this->params['tel']) && ($user->tel = $this->params['tel']);
        !empty($this->params['bank_card']) && ($user->bank_card = $this->params['bank_card']);
        !empty($this->params['id_card']) && ($user->id_card = $this->params['id_card']);
        $user->save();

        // 绑定角色
        $user->attachRole($role);

        return [];
    }

    public function reset(Request $request)
    {
        // 获取输入参数
        $old_password = $request->input('old_password');
        $new_password = $request->input('new_password');
        $dup_password = $request->input('dup_password');

        $user = Auth::user();

        if (!Hash::check($old_password, $user->password) ||
            ($new_password !== $dup_password)) {
            return false;
        }
        // 更新密码
        $user->password = bcrypt($new_password);
        $user->save();

        return [];
    }

}