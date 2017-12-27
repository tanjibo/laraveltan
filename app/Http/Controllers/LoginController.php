<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    public function showLoginForm()
    {

        return view('login.login');
    }


    public function username()
    {
        return 'mobile';
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login( Request $request )
    {
        $request[ 'remember' ] = true;
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            //判断用户是否锁定，是否为内部用户
            if (!auth()->user()->is_lrss_staff || !auth()->user()->status) {

                $this->guard()->logout();
                $request->session()->invalidate();
                flash('您账号被禁用或者不是内部员工')->overlay();
                return redirect('/login');


            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    public function logout( Request $request )
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/login');
    }


}
