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

    public function login(Request $request){
        $input = $request->only('email-or-user-id', 'password');

        $this->validate($request, [
            'email-or-user-id' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    
        $credentials = [];
    
        if (filter_var($input['email-or-user-id'], FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $input['email-or-user-id'];
        } else {
            $credentials['user_id'] = $input['email-or-user-id'];
        }
    
        if (Auth::attempt(array_merge($credentials, ['password' => $input['password']]))) {
            $user_type = Auth::user()->role;
            switch ($user_type) {
                case 'admin':
                case 'co_admin':
                    return redirect()->route('admin.home');
                case 'organizer':
                    return redirect()->route('organizer.home');
                case 'attendee':
                    return redirect()->route('attendee.home');
                default:
                    abort(401);
            }
        } else {
            return redirect()->route('login')->with('error-login', 'Incorrect email/user_id and/or password.');
        }
    }
}
