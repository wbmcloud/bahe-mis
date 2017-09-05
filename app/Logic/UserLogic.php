<?php
/**
 * Created by PhpStorm.
 * User: cloud
 * Date: 17/5/12
 * Time: 上午10:49
 */

namespace App\Logic;

use App\Common\Constants;
use App\Common\ParamsRules;
use App\Common\Utils;
use App\Events\ActionEvent;
use App\Exceptions\BaheException;
use App\Models\City;
use App\Models\InviteCode;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;

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
        $user->user_name = $params['user_name'];
        $user->password = bcrypt($params['password']);
        $user->role_id = $params['role_id'];
        $user->save();

        return $user;
    }

    /**
     * @param $params
     * @return User
     */
    public function createAgent($params)
    {
        // 判断邀请码是否有效
        $agent_logic = new AgentLogic();
        if (isset($params['invite_code']) && !empty($params['invite_code'])) {
            $agent_logic->getInviteCode($params['invite_code']);
        }

        // 创建用户
        $user = new User();
        $user->user_name = $params['user_name'];
        $user->password = bcrypt($params['password']);
        $user->city_id = $params['city_id'];
        $user->role_id = $params['role_id'];
        !empty($params['name']) && ($user->name = $params['name']);
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

    public function createGeneralAgent($params)
    {
        // 校验邀请码合法性
        $general_agent_logic = new GeneralAgentLogic();
        $invite_code = $general_agent_logic->getInviteCode($params['invite_code']);

        DB::beginTransaction();
        try {
            // 创建总代理
            $user = new User();
            $user->user_name = $params['user_name'];
            $user->password = bcrypt($params['password']);
            $user->city_id = $params['city_id'];
            $user->code = $params['invite_code'];
            $user->role_id = $params['role_id'];
            !empty($params['name']) && ($user->name = $params['name']);
            !empty($params['tel']) && ($user->tel = $params['tel']);
            !empty($params['bank_card']) && ($user->bank_card = $params['bank_card']);
            !empty($params['id_card']) && ($user->id_card = $params['id_card']);
            $user->save();

            $invite_code->is_used = Constants::COMMON_ENABLE;
            $invite_code->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new BaheException(BaheException::FAIL_CODE);
        }

        return $user;
    }

    public function createFirstAgent($params)
    {
        // 校验邀请码合法性
        $first_agent_logic = new FirstAgentLogic();
        $first_agent_logic->getInviteCode($params['invite_code']);

        DB::beginTransaction();
        try {
            // 创建总代理
            $user = new User();
            $user->user_name = $params['user_name'];
            $user->password = bcrypt($params['password']);
            $user->city_id = $params['city_id'];
            $user->code = !empty($params['code']) ? $params['code'] :
                Utils::getUniqueInviteCode($params['invite_code']);
            $user->invite_code = $params['invite_code'];
            $user->role_id = $params['role_id'];
            !empty($params['name']) && ($user->name = $params['name']);
            !empty($params['tel']) && ($user->tel = $params['tel']);
            !empty($params['bank_card']) && ($user->bank_card = $params['bank_card']);
            !empty($params['id_card']) && ($user->id_card = $params['id_card']);
            $user->save();

            $invite_code = new InviteCode();
            $invite_code->invite_code = $user->code;
            $invite_code->type = Constants::INVITE_CODE_TYPE_FIRST_AGENT;
            $invite_code->is_used = Constants::COMMON_ENABLE;
            $invite_code->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            throw new BaheException(BaheException::FAIL_CODE);
        }

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
        $this->checkUserIsCreated($params['user_name']);

        switch ($params['type']) {
            case Constants::ADD_USER_TYPE_ADMIN:
                if (!Auth::user()->hasRole(Constants::ROLE_SUPER)) {
                    throw new BaheException(BaheException::PERMISSION_FAIL_CODE);
                }
                $role = $this->getRoleByRoleName(Constants::ROLE_ADMIN);
                $params['role_id'] = $role->id;
                $user = $this->createAdmin($params);
                // 绑定角色
                $user->attachRole($role);
                return Utils::renderSuccess();
                break;
            case Constants::ADD_USER_TYPE_AGENT:
                if (!Auth::user()->hasRole([
                    Constants::ROLE_SUPER,
                    Constants::ROLE_ADMIN,
                ])) {
                    throw new BaheException(BaheException::PERMISSION_FAIL_CODE);
                }
                $role = $this->getRoleByRoleName(Constants::ROLE_AGENT);
                $params['role_id'] = $role->id;
                $user = $this->createAgent($params);

                // 绑定角色
                $user->attachRole($role);

                // 初始化账户信息
                $account_logic = new AccountLogic();
                $account_logic->createAccount([
                    'user_id' => $user->id,
                ]);
                return Utils::renderSuccess();
                break;
            case Constants::ADD_USER_TYPE_FIRST_AGENT:
                if (!Auth::user()->hasRole([
                    Constants::ROLE_SUPER,
                    Constants::ROLE_ADMIN,
                ])) {
                    throw new BaheException(BaheException::PERMISSION_FAIL_CODE);
                }
                $role = $this->getRoleByRoleName(Constants::ROLE_FIRST_AGENT);
                $params['role_id'] = $role->id;
                $user = $this->createFirstAgent($params);

                // 绑定角色
                $user->attachRole($role);

                // 初始化账户信息
                $account_logic = new AccountLogic();
                $account_logic->createAccount([
                    'user_id' => $user->id,
                ]);
                return Utils::renderSuccess();
                break;
            case Constants::ADD_USER_TYPE_GENERAL_AGENT:
                if (!Auth::user()->hasRole([
                    Constants::ROLE_SUPER,
                    Constants::ROLE_ADMIN,
                ])) {
                    throw new BaheException(BaheException::PERMISSION_FAIL_CODE);
                }
                $role = $this->getRoleByRoleName(Constants::ROLE_GENERAL_AGENT);
                $params['role_id'] = $role->id;
                $user = $this->createGeneralAgent($params);

                // 绑定角色
                $user->attachRole($role);

                // 初始化账户信息
                $account_logic = new AccountLogic();
                $account_logic->createAccount([
                    'user_id' => $user->id,
                ]);
                return Utils::renderSuccess();
                break;

            throw new BaheException(BaheException::TYPE_NOT_VALID_CODE);
        }
    }

    public function reset($params)
    {
        if ($params['new_password'] !== $params['dup_password']) {
            throw new BaheException(BaheException::USER_PASSWORD_CONFIRM_NOT_VALID_CODE);
        }

        $user = Auth::user();
        if (!Hash::check($params['old_password'], $user->password)) {
            throw new BaheException(BaheException::USER_PASSWORD_OLD_NOT_VALID_CODE);
        }

        // 更新密码
        $user->password = bcrypt($params['new_password']);
        $user->save();

        return Utils::renderSuccess();;
    }

    /**
     * @param $user
     * @return mixed
     * @throws BaheException
     */
    public function getRoleByUser($user)
    {
        $role = $user->roles()->first()->toArray();
        if (empty($role)) {
            throw new BaheException(BaheException::ROLE_NOT_EXIST_CODE);
        }
        return $role;
    }

    public function agree($params)
    {
        $user = Auth::user();
        !empty($params['is_accept']) && ($user->is_accept = Constants::COMMON_ENABLE);
        $user->save();

        return redirect(ParamsRules::IF_DASHBOARD);
    }

    protected function checkUserIsCreated($user_name)
    {
        $user = User::where('user_name', $user_name)->first();
        if (empty($user)) {
            return true;
        }
        throw new BaheException(BaheException::USER_EXIST_CODE);
    }
}