<?php

namespace App\Http\Controllers;

use App\Common\Constants;
use App\Common\ParamsRules;
use App\Events\LoginEvent;
use App\Exceptions\SlException;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        if (Auth::attempt([
            $this->username() => $this->params['user_name'],
                'password' => $this->params['password'],
                'status' => Constants::COMMON_ENABLE])) {
            //登录成功，触发事件
            event(new LoginEvent(Auth::user(), new Agent(), $request->getClientIp()));
            return redirect(ParamsRules::IF_DASHBOARD);
        }
        return redirect(ParamsRules::IF_USER_LOGIN)->with('message',
            SlException::$error_msg[SlException::LOGIN_USER_NAME_OR_PASSWD_INVALID]);
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
