@extends('layouts.auth')

@section('content')
<div class="nx-form">
    <div class="nx-form__badge" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="8" cy="15" r="4"/><path d="m11 12 8-8m-3 3 3 3"/></svg></div>
    <h1 class="nx-form__title">{{ __('Choose a new password') }}</h1>
    <p class="nx-form__sub">{{ __('Use at least 8 characters.') }}</p>

    <form method="POST" action="{{ route('password.update') }}" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="nx-field">
            <label for="email" class="form-label">{{ __('Email address') }}</label>
            <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   required autocomplete="email" autofocus>
            @error('email')
                <div class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>

        <div class="nx-field">
            <label for="password" class="form-label">{{ __('New password') }}</label>
            <div class="nx-input-wrap">
                <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   required autocomplete="new-password" minlength="8">
                <button type="button" class="nx-eye" data-toggle-password aria-label="Show password">Show</button>
            </div>
            @error('password')
                <div class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror
        </div>

        <div class="nx-field">
            <label for="password-confirm" class="form-label">{{ __('Confirm new password') }}</label>
            <div class="nx-input-wrap">
                <input id="password-confirm" type="password" name="password_confirmation"
                   class="form-control" required autocomplete="new-password" minlength="8">
                <button type="button" class="nx-eye" data-toggle-password aria-label="Show password">Show</button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary nx-submit">{{ __('Save password') }}</button>

        @if (Route::has('login'))
            <p class="nx-form__foot">
                <a class="nx-link" href="{{ route('login') }}">{{ __('Back to log in') }}</a>
            </p>
        @endif
    </form>
</div>
@endsection
