@extends('layouts.app')

@section('nav-links')
    @if (Route::has('register'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('register') }}">{{ __('Sign Up as Event Organizer') }}</a>
        </li>
    @endif
@endsection

@section('content')
<div class="form-container">
    <input type="checkbox" id="flip">
    <div class="cover">
        <div class="front">
            <img class="front-img" src="asset/background_blue.png" alt="">
            <div class="text">
                <img src="asset/elevento_logo_white.png" width="320px" alt=""><br>
                <span class="text-1">{{ __('Take your events to ') }}<br> {{ __('the next level with our ') }}<br> {{ __('optimization tools') }}</span>
                <span class="text-2">{{ __('Elevate your event game!') }}</span>
            </div>
        </div>
        <div class="back">
            <img class="back-img" src="asset/background_blue.png" alt="">
            <div class="text">
                <img src="asset/elevento_logo_white.png" width="320px" alt=""><br>
                <span class="text-1">{{ __('Take your events to ') }}<br> {{ __('new heights with our ') }}<br> {{ __('optimization app') }}</span>
            </div>
        </div>
    </div>
    <div class="forms">
        <div class="form-content">
            <div class="login-form">
                <div class="title">{{ __('Login') }}</div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="input-boxes">
                            @if(session('alert'))
                                <div class="alert alert-danger alert-dismissible fade m-0 show" role="alert">
                                    {{ session('alert') }}
                                </div>
                            @endif

                            <div class="input-box">
                                <i class="fas fa-user"></i>
                                <input id="email-login" type="text" placeholder="{{ __('Enter email or ID number') }}" class="@error('email-or-user-id') is-invalid @enderror" name="email-or-user-id" value="{{ old('email-or-user-id') }}" required autocomplete="email" autofocus>
                            </div>
                            @error('email-or-user-id')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="input-box">
                                <i class="fas fa-lock"></i>
                                <input id="password-login" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Enter password') }}">
                            </div>
                            @error('password')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="d-flex align-items-center justify-content-between w-100">
                                <div class="form-check mr-3">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <span class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </span>
                                </div>

                                @if (Route::has('password.request'))
                                    <div class="text">
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('Forgot Your Password?') }}
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="button input-box">
                                <input type="submit" value="{{ __('Login') }}">
                            </div>

                            <div class="text sign-up-text">{{ __('Don\'t have an account? ') }}<label for="flip">{{ __('Sign Up') }}</label></div>
                        </div>
                    </form>
                </div> 
            <div class="signup-form">
                <div class="title">{{ __('Sign Up as Attendee') }}</div>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="input-boxes">
                            @if(session('error-register'))
                                <div class="alert alert-danger alert-dismissible fade m-0 show" role="alert">
                                    {{ session('error-register') }}
                                </div>
                            @endif

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="input-box">
                                        <i class="fas fa-user"></i>
                                        <input id="user-id" type="text" placeholder="{{ __('Enter ID number') }}" class="@error('user-id') is-invalid @enderror" name="user-id" value="{{ old('user-id') }}" required autocomplete="name" autofocus>
                                    </div>
                                    @error('user-id')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <div class="input-box">
                                        <i class="fas fa-envelope"></i>
                                        <input id="email-register" type="email" placeholder="{{ __('Enter email') }}" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                    </div>
                                    @error('email')
                                        <span class="text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="input-box">
                                <i class="fas fa-lock"></i>
                                <input id="password-register" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="{{ __('Enter password') }}">
                            </div>
                            
                            <div class="input-box">
                                <i class="fas fa-lock"></i>
                                <input id="password-confirm" type="password" class="@error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm password') }}">
                            </div>
                            @error('password')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="mt-4">
                                {!! NoCaptcha::renderJs() !!}
                                {!! NoCaptcha::display() !!}
                            </div>
                            @error('g-recaptcha-response')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="terms" id="terms">
                                <span class="form-check-label" for="remember">
                                    {{ __('I Accept the ') }} <label><a href="#">{{ __('Terms and Conditions') }}</a></label>
                                </span>
                            </div>
                            
                            <div class="button input-box">
                                <input type="submit" value="{{ __('Register') }}">
                            </div>

                            <div class="text sign-up-text">{{ __('Already have an account? ') }}<label for="flip">{{ __('Login') }}</label></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
