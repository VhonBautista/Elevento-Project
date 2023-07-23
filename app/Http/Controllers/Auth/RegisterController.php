<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\CampusEntity;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    protected function register(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password-register' => ['required', 'string', 'min:8', 'confirmed'],
            'g-recaptcha-response' => ['required', 'captcha'],
            'terms' => ['required'],
            'user-id' => ['required', 'string', 'max:20',],
        ], [
            'password-register.confirmed' => 'Passwords does not match.',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
            'g-recaptcha-response.captcha' => 'The reCAPTCHA verification failed. Please try again.',
            'terms.required' => 'You must accept the terms and conditions to register.',
        ]);

        $inputUserId = strtoupper(trim($data['user-id']));

        $campusEntity = CampusEntity::where('user_id', $inputUserId)->first();

        if (!$campusEntity) {
            return redirect()->back()->withInput($data)->with('error-register', 'Ensure that you belong to PSU and that your user ID is valid.');
        }

        $existingUser = User::where('user_id', $inputUserId)->first();

        if ($existingUser) {
            return redirect()->back()->withInput($request->all())->with('error-register', 'User ID already has an existing account.');
        }
        
        $username = ucfirst(strtolower($campusEntity->firstname));

        $user = User::create([
            'user_id' => $inputUserId,
            'username' => $username,
            'email' => $data['email'],
            'password' => Hash::make($data['password-register']),
        ]);

        Auth::login($user);

        return redirect()->route('attendee.home');
    }
}
