@extends('layouts.app')

@section('content')
<div class="py-4 background-img">
    <div class="form-container">
        <input type="checkbox" name="flip" id="flip">
        <div class="cover">
            <div class="front">
                <img class="front-img" src="asset/flip_front.png" alt="">
                <div class="text">
                    <img src="asset/elevento_logo_white.png" width="320px" alt=""><br>
                    <span class="text-1">{{ __('Take your events to ') }}<br> {{ __('the next level with our ') }}<br> {{ __('optimization tools') }}</span>
                    <span class="text-2">{{ __('Elevate your event game!') }}</span>
                </div>
            </div>
            <div class="back">
                <img class="back-img" src="asset/flip_back.png" alt="">
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
                        <form method="POST" id="login-form" action="{{ route('login') }}">
                            @csrf
                            
                            <div class="input-boxes">
                                @if(session('error-login'))
                                <div class="alert alert-danger m-0 show alert-box w-100" role="alert">
                                    <span style="padding-right: 16px;">
                                        {{ session('error-login') }}
                                    </span>
                                </div>
                                @endif

                                <div class="input-box">
                                    <i class="fas fa-user"></i>
                                    <input id="email-login" type="text" placeholder="{{ __('Enter email or ID number') }}" class="@error('email-or-user-id') is-invalid @enderror" name="email-or-user-id" value="{{ old('email-or-user-id') }}" autocomplete="email" autofocus required>
                                </div>
                                @error('email-or-user-id')
                                <span class="text-danger fw-bold small" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                                <span id="email-login-error" class="text-danger fw-bold small"></span>
                                
                                <div class="input-box">
                                    <i class="fas fa-lock"></i>
                                    <input id="password-login" type="password" class="@error('password') is-invalid @enderror" name="password"  autocomplete="current-password" placeholder="{{ __('Enter password') }}" required>
                                </div>
                                @error('password')
                                <span class="text-danger fw-bold small" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                                <span id="password-login-error" class="text-danger fw-bold small"></span>
                                
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

                                <div class="text sign-up-text">
                                    {{ __('Don\'t have an account? ') }}
                                    <label for="flip">
                                        {{ __('Sign Up') }}
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div> 
                
                <div class="signup-form">
                    <div class="title">{{ __('Sign Up') }}</div>
                        <form method="POST" id="register-form" action="{{ route('register-user') }}">
                            @csrf

                            <div class="input-boxes">
                                @if(session('error-register'))
                                <div class="alert alert-danger m-0 show alert-box" role="alert">
                                    {{ session('error-register') }}
                                </div>
                                @endif

                                <div class="input-box">
                                    <i class="fas fa-user"></i>
                                    <input id="id-register" type="text" placeholder="{{ __('Enter ID number') }}" class="@error('user-id') is-invalid @enderror" name="user-id" value="{{ old('user-id') }}" autocomplete="name" autofocus required>
                                </div>
                                @error('user-id')
                                <span class="text-danger fw-bold small" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                                <span id="id-register-error" class="text-danger fw-bold small"></span>

                                <div class="input-box">
                                    <i class="fas fa-envelope"></i>
                                    <input id="email-register" type="email" placeholder="{{ __('Enter email') }}" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus required>
                                </div>
                                @error('email')
                                <span class="text-danger fw-bold small" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                                <span id="email-register-error" class="text-danger fw-bold small"></span>

                                <div class="input-box">
                                    <i class="fas fa-lock"></i>
                                    <input id="password-register" type="password" class="@error('password-register') is-invalid @enderror" name="password-register"   autocomplete="current-password" placeholder="{{ __('Enter password') }}" required>
                                </div>

                                @error('password-register')
                                <span class="text-danger fw-bold small" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror
                                <div class="d-flex justify-content-between">
                                    <div id="password-register-strength"></div>
                                    <div class="form-check">
                                        <input class="form-check-input" id="show-password-register" type="checkbox">
                                        <span class="small" for="show-password-register">
                                            {{ __('Show password') }}
                                        </span>
                                    </div>
                                </div>
                                <span id="password-register-error" class="text-danger fw-bold small"></span>

                                <div class="mt-2 captcha-box" style="">
                                    {!! NoCaptcha::renderJs() !!}
                                    {!! NoCaptcha::display() !!}
                                </div>
                                @error('g-recaptcha-response')
                                <span class="text-danger fw-bold small" role="alert">
                                    {{ $message }}
                                </span>
                                @enderror

                                <div class="form-check mt-3">
                                    <input class="form-check-input" id="terms-register" type="checkbox">
                                    <span class="form-check-label text" style="font-size: 14px;" for="remember">
                                        {{ __('I Accept the ') }}
                                        <label>
                                            <a href="#">{{ __('Terms and Conditions') }}</a>
                                        </label>
                                    </span>
                                </div>
                                <span id="terms-register-error" class="text-danger fw-bold small"></span>
                                
                                <div class="button input-box">
                                    <input id="submit-register" type="submit" value="{{ __('Register') }}">
                                </div>

                                <div class="text sign-up-text">
                                    {{ __('Already have an account? ') }}
                                    <label for="flip">
                                        {{ __('Login') }}
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        var checkbox = $('#flip');

        var checkboxValue = $.cookie('checkboxValue');
        if (checkboxValue === 'checked') {
            checkbox.prop('checked', true);
        }

        checkbox.change(function() {
            $.cookie('checkboxValue', checkbox.prop('checked') ? 'checked' : '', { expires: 1, path: '/' });
        });

        $('#register-form').submit(function() {
            $.cookie('checkboxValue', checkbox.prop('checked') ? 'checked' : '', { expires: 1, path: '/' });
        });

        // Login Form
        const $emailLoginInput = $('#email-login');
        const $passwordLoginInput = $('#password-login');
        const $emailLoginError = $('#email-login-error');
        const $passwordLoginError = $('#password-login-error');


        // Clear error message on focus
        $emailLoginInput.focus(function() {
            $emailLoginError.text('');
        });

        $passwordLoginInput.focus(function() {
            $passwordLoginError.text('');
        });


        // Form validation
        $('#login-form').on('submit', function (event) {
            if (!validateLoginForm()) {
                event.preventDefault();
            }
        });

        function validateLoginForm() {
            let isValid = true;

            if ($emailLoginInput.value.trim() === '') {
                $emailLoginInput.classList.add('is-invalid');
                $emailLoginError.textContent = 'Credentials are required.';
                isValid = false;
            } else {
                $emailLoginInput.classList.remove('is-invalid');
                $emailLoginError.textContent = '';
            }

            if ($passwordLoginInput.value.trim() === '') {
                $passwordLoginInput.classList.add('is-invalid');
                $passwordLoginError.textContent = 'Credentials are required.';
                isValid = false;
            } else {
                $passwordLoginInput.classList.remove('is-invalid');
                $passwordLoginError.textContent = '';
            }

            return isValid;
        }


        // Register Form
        const $idRegisterInput = $('#id-register');
        const $emailRegisterInput = $('#email-register');
        const $passwordRegisterInput = $('#password-register');
        const $termsRegister = $('#terms-register');
        const $idRegisterError = $('#id-register-error');
        const $emailRegisterError = $('#email-register-error');
        const $passwordRegisterError = $('#password-register-error');
        const $termsRegisterError = $('#terms-register-error');
        const $passwordRegisterStrength = $('#password-register-strength');
        const $submitButton = $('#submit-register');

        // Show password register form
        $('#show-password-register').click(function() {
            if ($(this).is(':checked')) {
                $passwordRegisterInput.attr('type', 'text');
            } else {
                $passwordRegisterInput.attr('type', 'password');
            }
        });

        // Clear error message on focus
        $idRegisterInput.focus(function() {
            $idRegisterError.text('');
        });

        $emailRegisterInput.focus(function() {
            $emailRegisterError.text('');
        });

        $passwordRegisterInput.focus(function() {
            $passwordRegisterError.text('');
        });


        // Password strength
        $passwordRegisterInput.on('input', function () {
            const password = $passwordRegisterInput.val().trim();

            if (password !== '') {
                $passwordRegisterInput.removeClass('is-invalid');
                $passwordRegisterError.text('');
            }
            
            let strengthMessage = '';
            let passwordStrength = 0;

            if (password.length > 8) {
            passwordStrength++;
            }

            if (password.match(/[a-z]/)) {
            passwordStrength++;
            }

            if (password.match(/[A-Z]/)) {
            passwordStrength++;
            }

            if (password.match(/\d/)) {
            passwordStrength++;
            }

            if (password.match(/[!@#$%^&*()_+{}\[\]:;<>,.?~\\|\-=]/)) {
            passwordStrength++;
            }

            switch (passwordStrength) {
            case 1:
                strengthMessage = 'Weak password';
                $passwordRegisterStrength.removeClass().addClass('text-danger fw-bold small');
                break;
            case 2:
                strengthMessage = 'Moderate password';
                $passwordRegisterStrength.removeClass().addClass('text-warning fw-bold small');
                break;
            case 3:
            case 4:
                strengthMessage = 'Strong password';
                $passwordRegisterStrength.removeClass().addClass('text-success fw-bold small');
                break;
            case 5:
                strengthMessage = 'Very strong password';
                break;
            default:
                strengthMessage = '';
            }

            $passwordRegisterStrength.text(strengthMessage);
        });

        // Form validation
        $('#register-form').on('submit', function (event) {
            if (!validateRegisterForm()) {
                event.preventDefault();
            }
        });

        function validateRegisterForm() {
            let isValid = true;

            if ($idRegisterInput.val().trim() === '') {
                $idRegisterInput.addClass('is-invalid');
                $idRegisterError.text('ID number is required.');
                isValid = false;
            } else {
                $idRegisterInput.removeClass('is-invalid');
                $idRegisterError.text('');
            }

            if ($emailRegisterInput.val().trim() === '') {
                $emailRegisterInput.addClass('is-invalid');
                $emailRegisterError.text('Email address is required.');
                isValid = false;
            } else {
                $emailRegisterInput.removeClass('is-invalid');
                $emailRegisterError.text('');
            }

            if ($passwordRegisterInput.val().trim() === '') {
                $passwordRegisterInput.addClass('is-invalid');
                $passwordRegisterError.text('Password is required.');
                isValid = false;
            } else if ($passwordRegisterInput.val().trim().length < 8) {
                $passwordRegisterInput.addClass('is-invalid');
                $passwordRegisterError.text('Password should not be less than 8 characters.');
                isValid = false;
            } else {
                $passwordRegisterInput.removeClass('is-invalid');
                $passwordRegisterError.text('');
            }

            if (!$termsRegister.is(':checked')) {
                $termsRegisterError.text('You must accept the Terms and Conditions.');
                isValid = false;
            } else {
                $termsRegisterError.text('');
            }

            return isValid;
        }
    });
</script>
@endsection
