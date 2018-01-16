<?php

namespace App\Http\Controllers;

use App\Common\Constants;
use App\Common\ParamsRules;
use App\Events\LoginEvent;
use App\Exceptions\BaheException;
use App\Models\User;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use ThrottlesLogins;

    protected function username()
    {
        return 'user_name';
    }
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect(ParamsRules::IF_DASHBOARD);
        }
        return [];
    }

    public function login(Request $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $user = User::where([
            $this->username() => $this->params['user_name'],
        ])->first();
        if (!empty($user) && Hash::check($this->params['password'], $user->password)) {
            if ($user->status == Constants::COMMON_DISABLE) {
                return redirect(ParamsRules::IF_USER_LOGIN)->with('message',
                    BaheException::$error_msg[BaheException::LOGIN_USER_ACCOUNT_FROZEN]);
            }
            Auth::login($user);
            //登录成功，触发事件
            event(new LoginEvent(Auth::user(), new Agent(), $request->getClientIp()));
            return redirect(ParamsRules::IF_DASHBOARD);
        }
        return redirect(ParamsRules::IF_USER_LOGIN)->with('message',
            BaheException::$error_msg[BaheException::LOGIN_USER_NAME_OR_PASSWD_INVALID]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect(ParamsRules::IF_USER_LOGIN);
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
