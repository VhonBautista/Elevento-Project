@extends('layouts.app')

@section('content')
<div class="py-4 background-img">
    <div class="form-container" style="width: 500px;">
        <div class="forms">
            <div class="form-content">
                <div class="px-4 w-100">
                    <div class="title">{{ __('Reset Password') }}</div>
                        <form method="POST" id="reset-form" action="{{ route('password.email') }}">
                            @csrf
                            
                            <div class="input-boxes">
                                @if (session('status'))
                                    <div class="alert alert-success m-0 show alert-box w-100" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <div class="input-box">
                                    <i class="fas fa-user"></i>
                                    <input id="email" type="email" placeholder="{{ __('Enter email address') }}" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autocomplete="email" autofocus required>
                                </div>
                                @error('email')
                                    <span class="text-danger fw-bold small" role="alert">{{ $message }}</span>
                                @enderror
                                <span id="email-error" class="text-danger fw-bold small"></span>
                                
                                <div class="button input-box">
                                    <input type="submit" value="{{ __('Send Password Reset Link') }}">
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
        // Validations
        const $emailInput = $('#email');
        const $emailError = $('#email-error');

        // Clear error message on focus
        $emailInput.focus(function() {
            $emailError.text('');
        });

        $('#reset-form').on('submit', function (event) {
            if (!validateResetForm()) {
                event.preventDefault();
            }
        });

        function validateResetForm() {
            let isValid = true;
            const emailValue = $emailInput.val().trim();

            if (emailValue === '') {
                $emailInput.addClass('is-invalid');
                $emailError.text('Email address is required.');
                isValid = false;
            } else {
                $emailInput.removeClass('is-invalid');
                $emailError.text('');
            }

            return isValid;
        }

    });
</script>
@endsection
