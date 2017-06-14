<?php

namespace App\Http\Controllers;

use App\Common\Constants;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        return 'name';
    }
    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect(Constants::LOGIN_REDIRECT_URI);
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
            $this->username() => $this->params['name'],
            'password' => $this->params['password'],
            'status' => Constants::COMMON_ENABLE])) {
            return redirect(Constants::LOGIN_REDIRECT_URI);
        }
        return redirect(Constants::LOGIN_URI);
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

        return redirect(Constants::LOGIN_URI);
    }

    protected function guard()
    {
        return Auth::guard();
    }
}
