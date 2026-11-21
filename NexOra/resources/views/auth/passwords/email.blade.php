@extends('layouts.auth')

@section('content')
<div class="nx-form">
    <div class="nx-form__badge" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></div>
    <h1 class="nx-form__title">{{ __('Reset your password') }}</h1>
    <p class="nx-form__sub">{{ __('Enter the email you registered with and we will send you a reset link.') }}</p>

    @if (session('status'))
        <div class="alert alert-success" role="status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" novalidate>
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

        <button type="submit" class="btn btn-primary nx-submit">{{ __('Send reset link') }}</button>

        @if (Route::has('login'))
            <p class="nx-form__foot">
                <a class="nx-link" href="{{ route('login') }}">{{ __('Back to log in') }}</a>
            </p>
        @endif
    </form>
</div>
@endsection
