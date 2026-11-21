@extends('layouts.auth')

@section('content')
<div class="nx-form">
    <div class="nx-form__badge" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/></svg></div>
    <h1 class="nx-form__title">{{ __('Confirm your password') }}</h1>
    <p class="nx-form__sub">{{ __('This area is protected. Enter your password to continue.') }}</p>

    <form method="POST" action="{{ route('password.confirm') }}" novalidate>
        @csrf

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
                   required autocomplete="current-password" autofocus>
                <button type="button" class="nx-eye" data-toggle-password aria-label="Show password">Show</button>
            </div>
            @error('password')
                <div class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary nx-submit">{{ __('Confirm') }}</button>
    </form>
</div>
@endsection
