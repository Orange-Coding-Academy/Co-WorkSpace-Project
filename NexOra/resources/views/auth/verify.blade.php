@extends('layouts.auth')

@section('content')
<div class="nx-form">
    <div class="nx-form__badge" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></div>
    <h1 class="nx-form__title">{{ __('Verify your email') }}</h1>
    <p class="nx-form__sub">{{ __('We sent a verification link to your email address. Open it to activate your account.') }}</p>

    @if (session('resent'))
        <div class="alert alert-success" role="status">
            {{ __('A new verification link is on its way to your email.') }}
        </div>
    @endif

    <form method="POST" action="{{ route('verification.resend') }}">
        @csrf
        <p class="nx-form__hint">{{ __('Nothing in your inbox or spam folder?') }}</p>
        <button type="submit" class="btn btn-primary nx-submit">{{ __('Send a new link') }}</button>
    </form>

    @auth
        <p class="nx-form__foot">
            <a class="nx-link" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{ __('Log out') }}
            </a>
        </p>
    @endauth
</div>
@endsection
