<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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

    public function login(Request $data){
        $credentials = [];
    
        if (filter_var($data['email-or-user-id'], FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $data['email-or-user-id'];
        } else {
            $credentials['user_id'] = $data['email-or-user-id'];
        }
        
        if (Auth::attempt(array_merge($credentials, ['password' => $data['password']]))) {
            return $this->authenticated($data, Auth::user());
        } else {
            return redirect()->route('login')->with('error-login', 'Invalid credentials. Please double-check your email/user ID and password.')->withInput();
        }
    }

    protected function authenticated(Request $request, $user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.home');
            case 'co_admin':
                return redirect()->route('co_admin.home');
            case 'organizer':
                return redirect()->route('organizer.home');
            case 'attendee':
                return redirect()->route('attendee.home');
            default:
                abort(401);
        }
    }

    public function showLoginForm()
    {
        if (auth()->check()) {
            return $this->authenticated(request(), auth()->user());
        }
        return view('auth.login');
    }
}
