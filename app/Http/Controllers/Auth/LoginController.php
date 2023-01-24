<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //check if the user status is active or not
    protected function credentials(Request $request)
    {
        return ['email' => $request->email, 'password' => $request->password, 'status' => 'active'];
    }

    //override the default login method
    public function showLoginForm()
    {
        return view('auth.login', [
            'page' => __('Login')
        ]);
    }
    
    //override the default logout method
    public function logout(Request $request)
    {
        $locale = session('locale');
        $this->guard()->logout();
        $request->session()->invalidate();
        if ($locale) session(['locale' => $locale]);
        return $this->loggedOut($request) ?: redirect('/');
    }
}
