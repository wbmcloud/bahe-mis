<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/12
 * Time: 上午10:49
 */

namespace App\Logic;

use App\Common\Constants;
use App\Exceptions\SlException;
use App\Models\City;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserLogic extends BaseLogic
{
    /**
     * @return mixed
     */
    public function getOpenCities()
    {
        $cities = City::where('status', Constants::COMMON_ENABLE)->get();
        return $cities;
    }

    /**
     * @param $params
     * @return User
     */
    public function createAdmin($params)
    {
        // 创建用户
        $user = new User();
        $user->name = $params['name'];
        $user->password = bcrypt($params['password']);
        $user->save();

        return $user;
    }

    /**
     * @param $params
     * @return User
     */
    public function createAgent($params)
    {
        // 创建用户
        $user = new User();
        $user->name = $params['name'];
        $user->password = bcrypt($params['password']);
        $user->city_id = $params['city_id'];
        !empty($params['invite_code']) && ($user->invite_code = $params['invite_code']);
        !empty($params['uin']) && ($user->uin = $params['uin']);
        !empty($params['wechat']) && ($user->wechat = $params['wechat']);
        !empty($params['uin_group']) && ($user->uin_group = $params['uin_group']);
        !empty($params['tel']) && ($user->tel = $params['tel']);
        !empty($params['bank_card']) && ($user->bank_card = $params['bank_card']);
        !empty($params['id_card']) && ($user->id_card = $params['id_card']);
        $user->save();

        return $user;
    }

    /**
     * @param $role_name
     * @return mixed
     */
    public function getRoleByRoleName($role_name)
    {
        return Role::where('name', $role_name)->first();
    }

    public function add($params)
    {
        $role = $this->getRoleByRoleName($params['role_name']);
        if (empty($role)) {
            throw new SlException(SlException::ROLE_NOT_EXIST_CODE);
        }

        if ($role->name === Constants::ROLE_SUPER) {
            throw new SlException(SlException::ROLE_NOT_VALID_CODE);
        }

        if ($role->name === Constants::ROLE_ADMIN) {
            $user = $this->createAdmin($params);
        } elseif ($role->name === Constants::ROLE_AGENT) {
            $user = $this->createAgent($params);
        }

        // 绑定角色
        $user->attachRole($role);

        return [];
    }

    public function reset($params)
    {
        if ($params['new_password'] !== $params['dup_password']) {
            throw new SlException(SlException::USER_PASSWORD_CONFIRM_NOT_VALID_CODE);
        }

        $user = Auth::user();
        if (!Hash::check($params['old_password'], $user->password)) {
            throw new SlException(SlException::USER_PASSWORD_OLD_NOT_VALID_CODE);
        }

        // 更新密码
        $user->password = bcrypt($params['new_password']);
        $user->save();

        return [];
    }

    /**
     * @param $user
     * @return mixed
     * @throws SlException
     */
    public function getRoleByUser($user)
    {
        $role = $user->roles()->first()->toArray();
        if (empty($role)) {
            throw new SlException(SlException::ROLE_NOT_EXIST_CODE);
        }
        return $role;
    }

}