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
                <span class="text-1">Take your events to <br> the next level with our <br> optimization tools</span>
                <span class="text-2">Elevate your event game!</span>
            </div>
        </div>
        <div class="back">
            <img class="back-img" src="asset/background_blue.png" alt="">
            <div class="text">
                <img src="asset/elevento_logo_white.png" width="320px" alt=""><br>
                <span class="text-1">Take your events to <br> new heights with our <br> optimization app</span>
            </div>
        </div>
    </div>
    <div class="forms">
        <div class="form-content">
            <div class="login-form">
                <div class="title">Login</div>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="input-boxes">
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade m-0 show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="input-box">
                                <i class="fas fa-envelope"></i>
                                <input id="email" type="email" placeholder="{{ __('Enter email address') }}" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="input-box">
                                <i class="fas fa-lock"></i>
                                <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter password">
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
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

                            <div class="text sign-up-text">Don't have an account? <label for="flip">{{ __('Sign Up') }}</label></div>
                        </div>
                    </form>
                </div> 
            <div class="signup-form">
                <div class="title">{{ __('Sign Up as Attendee') }}</div>
                    <form action="#">
                        <div class="input-boxes">
                            <div class="input-box">
                                <i class="fas fa-user"></i>
                                <input type="text" placeholder="Enter your name" required>
                            </div>
                            <div class="input-box">
                                <i class="fas fa-envelope"></i>
                                <input type="text" placeholder="Enter your email" required>
                            </div>
                            <div class="input-box">
                                <i class="fas fa-lock"></i>
                                <input type="password" placeholder="Enter your password" required>
                            </div>
                            <div class="button input-box">
                                <input type="submit" value="{{ __('Register') }}">
                            </div>
                            <div class="text sign-up-text">Already have an account? <label for="flip">{{ __('Login') }}</label></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
