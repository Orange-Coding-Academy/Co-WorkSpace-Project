{{--
    resources/views/layouts/member.blade.php

    Layout for customer (member) pages: public-style header, NO sidebar.
    - Header is always solid (the landing page's transparent-over-hero mode is not used here).
    - Header classes use the `nx-` prefix so Bootstrap (loaded by member pages) can't break them.
    - Used by: @extends('layouts.member')  +  @section('title') ... @endsection  +  @section('content')
--}}
@php
    $nxUser = auth()->user();

    $nxMemberMain = [
        ['member.bookings.index', 'My Bookings', ['member.bookings.*', 'member.checkout.*']],
        ['member.memberships.index', 'My Memberships', ['member.memberships.*']],
    ];
    $nxMemberMore = [
        ['member.workspace-ai.index', 'Workspace Finder', ['member.workspace-ai.*']],
        ['member.reviews.index', 'My Reviews', ['member.reviews.*']],
        ['member.profile.index', 'My Profile', ['member.profile.*']],
    ];

    $nxTitle = trim($__env->yieldContent('title', 'My Account'));
    $nxTitleFull = stripos($nxTitle, 'nexora') === false ? $nxTitle . ' | NexOra' : $nxTitle;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    dir="{{ in_array(substr(app()->getLocale(), 0, 2), ['ar', 'he', 'fa', 'ur']) ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{!! $nxTitleFull !!}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap"
        rel="stylesheet">

    {{-- Bootstrap is loaded here once. Remove the duplicate <link> from each page's @push('styles') later. --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Needed by the chat widget only (the dashboard css files are intentionally NOT loaded). --}}
    <link rel="stylesheet" href="{{ asset('css/nexora/design-tokens.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/chat-widget.css') }}?v=20261125-ui2">

    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-shared-11.css') }}?v=20261125-chevron3">

    @stack('styles')
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}?v=20261125-green3">
    <link rel="stylesheet" href="{{ asset('css/nexora/role-polish.css') }}?v=20261125-ui7">
</head>

<body class="nx-member-body">
    <a class="nx-skip" href="#nx-main">Skip to main content</a>

    <header class="nx-header" id="nxHeader">
        <div class="nx-header__inner">
            <a href="{{ url('/') }}" class="nx-logo" aria-label="NexOra home">
                <svg class="nx-brand-mark" viewBox="0 0 40 40" aria-hidden="true">
                    <rect x="1" y="1" width="38" height="38" rx="11" />
                    <path class="nx-brand-mark__glyph" d="M12 29V11l16 18V11" />
                </svg>
                NexOra
            </a>

            <nav aria-label="Primary">
                <ul class="nx-nav">
                    @if (Route::has('explore'))
                        <li><a class="nx-nav__link {{ request()->routeIs('explore') ? 'is-active' : '' }}"
                                href="{{ route('explore') }}">Explore</a></li>
                    @endif

                    @foreach ($nxMemberMain as [$nxName, $nxLabel, $nxPatterns])
                        @if (Route::has($nxName))
                            <li><a class="nx-nav__link {{ request()->routeIs(...$nxPatterns) ? 'is-active' : '' }}"
                                    href="{{ route($nxName) }}"
                                    @if (request()->routeIs(...$nxPatterns)) aria-current="page" @endif>{{ $nxLabel }}</a>
                            </li>
                        @endif
                    @endforeach

                    <li>
                        <details class="nx-account">
                            <summary class="nx-nav__link">
                                {{ \Illuminate\Support\Str::before(trim($nxUser->name ?? 'Account'), ' ') ?: 'Account' }}
                                <svg class="nx-account-chevron" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true" focusable="false">
                                    <path d="m6 9 6 6 6-6" />
                                </svg>
                            </summary>
                            <div class="nx-account__menu">
                                @foreach ($nxMemberMore as [$nxName, $nxLabel, $nxPatterns])
                                    @if (Route::has($nxName))
                                        <a href="{{ route($nxName) }}"
                                            class="{{ request()->routeIs(...$nxPatterns) ? 'is-active' : '' }}">{{ $nxLabel }}</a>
                                    @endif
                                @endforeach

                                @if (Route::has('logout'))
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit">Log out</button>
                                    </form>
                                @endif
                            </div>
                        </details>
                    </li>
                </ul>
            </nav>

            <button type="button" class="nx-burger" id="nxBurger" aria-expanded="false" aria-controls="nxMobile"
                aria-label="Open menu">
                <span></span><span></span><span></span>
            </button>
        </div>

        <nav class="nx-mobile" id="nxMobile" aria-label="Mobile">
            <div class="nx-mobile__inner">
                @if (Route::has('explore'))
                    <a href="{{ route('explore') }}">Explore <span aria-hidden="true">&rarr;</span></a>
                @endif

                @foreach (array_merge($nxMemberMain, $nxMemberMore) as [$nxName, $nxLabel, $nxPatterns])
                    @if (Route::has($nxName))
                        <a href="{{ route($nxName) }}"
                            class="{{ request()->routeIs(...$nxPatterns) ? 'is-active' : '' }}">{{ $nxLabel }}
                            <span aria-hidden="true">&rarr;</span></a>
                    @endif
                @endforeach

                @if (Route::has('logout'))
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Log out</button>
                    </form>
                @endif
            </div>
        </nav>
    </header>

    <main class="nx-member-main" id="nx-main" tabindex="-1">
        @yield('content')
    </main>

    <script>
        (function() {
            var burger = document.getElementById('nxBurger');
            var panel = document.getElementById('nxMobile');

            function setMenu(open) {
                burger.setAttribute('aria-expanded', String(open));
                burger.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
                panel.classList.toggle('is-open', open);
            }
            burger.addEventListener('click', function() {
                setMenu(burger.getAttribute('aria-expanded') !== 'true');
            });
            window.matchMedia('(min-width: 1024px)').addEventListener('change', function(m) {
                if (m.matches) setMenu(false);
            });

            // Account dropdown: close on outside click / Escape
            document.addEventListener('click', function(e) {
                document.querySelectorAll('.nx-account[open]').forEach(function(d) {
                    if (!d.contains(e.target)) d.removeAttribute('open');
                });
            });
            document.addEventListener('keydown', function(e) {
                if (e.key !== 'Escape') return;
                document.querySelectorAll('.nx-account[open]').forEach(function(d) {
                    d.removeAttribute('open');
                });
                setMenu(false);
            });
        })();
    </script>

    @stack('scripts')

    @include('Member.partials.chat-widget')
</body>

</html>
