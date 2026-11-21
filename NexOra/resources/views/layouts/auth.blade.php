<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'NexOra') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Auth pages only: this file is NOT loaded by layouts/app.blade.php --}}
<link href="{{ asset('css/nexora/auth.css') }}" rel="stylesheet">
</head>
<body class="nx-page-content">
    <div id="app" class="nx-auth">
        <aside class="nx-auth__brand" aria-label="NexOra">
            <a class="nx-auth__logo" href="{{ url('/') }}">
                <svg viewBox="0 0 30 30" aria-hidden="true"><rect x="1" y="1" width="28" height="28" rx="6"/><rect class="door" x="17" y="11" width="7" height="18" rx="1"/></svg>
                {{ config('app.name', 'NexOra') }}
            </a>
            <div>
                <h2>Find your space. <em>Make your next move.</em></h2>
                <p>One account for booking rooms, holding memberships, or running the workspaces you manage.</p>
            </div>
            <ul class="nx-auth__roles">
                <li><strong>Members</strong> Book rooms and keep your memberships in one place.</li>
                <li><strong>Managers</strong> List spaces, review bookings and plans.</li>
            </ul>
        </aside>

        <div class="nx-auth__main">
            <nav class="nx-auth__bar" aria-label="Account">
                <button class="navbar-toggler" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent"
                        aria-expanded="false"
                        aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse nx-auth__links" id="navbarSupportedContent">
                    <div class="d-flex align-items-center gap-1">
                        <ul class="navbar-nav flex-row gap-1 ms-auto">
                            @guest
                                @if (Route::has('login'))
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">{{ __('Log in') }}</a>
                                    </li>
                                @endif
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                       data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            {{ __('Log out') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>

            <main class="nx-auth__body">
                @yield('content')
            </main>
        </div>
    </div>
    <script>
        document.addEventListener('click', function (e) {
            var b = e.target.closest('[data-toggle-password]');
            if (!b) return;
            var input = b.parentElement.querySelector('input');
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            b.textContent = show ? 'Hide' : 'Show';
            b.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
        });
    </script>
    @stack('scripts')
</body>
</html>
