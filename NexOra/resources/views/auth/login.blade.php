@extends('layouts.auth')

@section('content')
<div class="nx-form">
    <div class="nx-form__badge" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg></div>
    <h1 class="nx-form__title">{{ __('Log in') }}</h1>
    <p class="nx-form__sub">{{ __('Welcome back. Enter your details to continue.') }}</p>

    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <div class="nx-field">
            <label for="email" class="form-label">{{ __('Email address') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   required autocomplete="email" autofocus>
            @error('email')
                <div class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>

        <div class="nx-field">
            <div class="nx-field__row">
                <label for="password" class="form-label mb-0">{{ __('Password') }}</label>
                @if (Route::has('password.request'))
                    <a class="nx-link" href="{{ route('password.request') }}">{{ __('Forgot password?') }}</a>
                @endif
            </div>
            <div class="nx-input-wrap">
                <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required autocomplete="current-password">
                <button type="button" class="nx-eye" data-toggle-password aria-label="Show password">Show</button>
            </div>
            @error('password')
                <div class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check nx-field">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">{{ __('Keep me logged in') }}</label>
        </div>

        <button type="submit" class="btn btn-primary nx-submit">{{ __('Log in') }}</button>

        @if (Route::has('register'))
            <p class="nx-form__foot">
                {{ __('New to NexOra?') }}
                <a class="nx-link" href="{{ route('register') }}">{{ __('Create an account') }}</a>
            </p>
        @endif
    </form>
</div>
@endsection
