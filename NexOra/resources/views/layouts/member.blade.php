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
    <link rel="stylesheet" href="{{ asset('css/nexora/chat-widget.css') }}">

    <style>
        :root {
            --nxh-primary: #315C4D;
            --nxh-primary-hover: #274B3F;
            --nxh-accent: #C58A62;
            --nxh-bg: #F5F7F3;
            --nxh-card: #FFFFFF;
            --nxh-text: #1D2924;
            --nxh-text-secondary: #53615A;
            --nxh-border: #E2E8E2;
            --nxh-border-strong: #C9D4CC;
            --nxh-divider: #E9EEE9;
            --nxh-height: 72px;
            --nxh-font-display: 'Manrope', 'Noto Sans Arabic', system-ui, sans-serif;
            --nxh-font-body: 'Inter', 'Noto Sans Arabic', system-ui, sans-serif;
        }

        body.nx-member-body {
            margin: 0;
            background: var(--nxh-bg);
            color: var(--nxh-text);
            font-family: var(--nxh-font-body);
            -webkit-font-smoothing: antialiased;
        }

        /* ---------- Header ---------- */
        .nx-header {
            position: fixed;
            inset: 0 0 auto 0;
            z-index: 1030;
            background: rgba(245, 247, 243, .94);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 1px 0 var(--nxh-border);
        }

        .nx-header__inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
            height: var(--nxh-height);
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 28px;
        }

        .nx-logo {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: var(--nxh-text);
            font-family: var(--nxh-font-display);
            font-weight: 800;
            font-size: 21px;
            letter-spacing: -.03em;
        }

        .nx-logo:hover {
            color: var(--nxh-text);
        }

        .nx-logo svg {
            width: 30px;
            height: 30px;
        }

        .nx-logo svg rect {
            fill: none;
            stroke: currentColor;
            stroke-width: 1.6;
        }

        .nx-logo svg .nx-door {
            fill: var(--nxh-accent);
            stroke: none;
        }

        .nx-nav {
            display: flex;
            align-items: center;
            gap: 28px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .nx-nav__link {
            position: relative;
            display: inline-flex;
            align-items: center;
            min-height: 44px;
            padding: 0;
            font-size: 14px;
            font-weight: 500;
            color: var(--nxh-text-secondary);
            text-decoration: none;
            background: none;
            border: 0;
            cursor: pointer;
            transition: color .25s;
        }

        .nx-nav__link:hover,
        .nx-nav__link.is-active {
            color: var(--nxh-text);
        }

        .nx-nav__link::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: 8px;
            height: 1px;
            background: var(--nxh-accent);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .3s;
        }

        .nx-nav__link:hover::after,
        .nx-nav__link.is-active::after {
            transform: scaleX(1);
        }

        /* Account dropdown */
        .nx-account {
            position: relative;
        }

        .nx-account summary {
            list-style: none;
            cursor: pointer;
        }

        .nx-account summary::-webkit-details-marker {
            display: none;
        }

        .nx-account summary.nx-nav__link::after {
            position: static;
            height: auto;
            background: none;
            transform: none;
            content: "⌄";
            margin-inline-start: 7px;
            transition: transform .25s;
        }

        .nx-account[open] summary.nx-nav__link::after {
            transform: rotate(180deg);
        }

        .nx-account__menu {
            position: absolute;
            inset-block-start: calc(100% + 8px);
            inset-inline-end: 0;
            width: 220px;
            padding: 8px;
            background: var(--nxh-card);
            border: 1px solid var(--nxh-border);
            border-radius: 12px;
            box-shadow: 0 16px 40px rgba(21, 42, 35, .16);
        }

        .nx-account__menu a,
        .nx-account__menu button {
            display: block;
            width: 100%;
            padding: 10px 12px;
            border: 0;
            border-radius: 7px;
            background: none;
            text-align: start;
            font: inherit;
            font-size: 13px;
            color: var(--nxh-text-secondary);
            text-decoration: none;
            cursor: pointer;
        }

        .nx-account__menu a:hover,
        .nx-account__menu a.is-active {
            background: var(--nxh-bg);
            color: var(--nxh-primary);
        }

        .nx-account__menu form {
            margin: 6px 0 0;
            padding-top: 6px;
            border-top: 1px solid var(--nxh-divider);
        }

        .nx-account__menu button {
            color: #912018;
        }

        .nx-account__menu button:hover {
            background: #FDEDEC;
        }

        /* Burger */
        .nx-burger {
            display: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1px solid var(--nxh-border-strong);
            background: transparent;
            color: var(--nxh-text);
            cursor: pointer;
            place-items: center;
            align-content: center;
        }

        .nx-burger span {
            display: block;
            width: 16px;
            height: 1.5px;
            background: currentColor;
            transition: transform .3s, opacity .3s;
        }

        .nx-burger span+span {
            margin-top: 4px;
        }

        .nx-burger[aria-expanded="true"] span:nth-child(1) {
            transform: translateY(5.5px) rotate(45deg);
        }

        .nx-burger[aria-expanded="true"] span:nth-child(2) {
            opacity: 0;
        }

        .nx-burger[aria-expanded="true"] span:nth-child(3) {
            transform: translateY(-5.5px) rotate(-45deg);
        }

        /* Mobile panel */
        .nx-mobile {
            display: none;
            background: var(--nxh-bg);
            border-top: 1px solid var(--nxh-border);
            max-height: calc(100dvh - var(--nxh-height));
            overflow-y: auto;
        }

        .nx-mobile.is-open {
            display: block;
        }

        .nx-mobile__inner {
            padding: 0 28px 24px;
        }

        .nx-mobile a {
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 56px;
            border-bottom: 1px solid var(--nxh-divider);
            font-family: var(--nxh-font-display);
            font-weight: 700;
            font-size: 18px;
            color: var(--nxh-text);
            text-decoration: none;
        }

        .nx-mobile a.is-active {
            color: var(--nxh-primary);
        }

        .nx-mobile a span {
            color: var(--nxh-accent);
        }

        .nx-mobile form {
            margin: 20px 0 0;
        }

        .nx-mobile form button {
            width: 100%;
            min-height: 44px;
            border: 1px solid var(--nxh-border-strong);
            border-radius: 999px;
            background: transparent;
            font-family: var(--nxh-font-display);
            font-weight: 700;
            font-size: 14px;
            color: var(--nxh-text);
            cursor: pointer;
        }

        @media (max-width: 1023px) {
            .nx-nav {
                display: none;
            }

            .nx-burger {
                display: grid;
            }
        }

        @media (max-width: 767px) {
            :root {
                --nxh-height: 64px;
            }

            .nx-header__inner {
                padding: 0 20px;
            }

            .nx-mobile__inner {
                padding: 0 20px 24px;
            }
        }

        /* Content area: leave room for the fixed header */
        .nx-member-main {
            padding-top: var(--nxh-height);
            min-height: 100vh;
        }

        .nx-skip {
            position: absolute;
            left: 16px;
            top: -60px;
            z-index: 2000;
            background: var(--nxh-primary);
            color: #fff;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
        }

        .nx-skip:focus {
            top: 12px;
        }

        .nx-header :focus-visible,
        .nx-mobile :focus-visible {
            outline: 2px solid var(--nxh-accent);
            outline-offset: 2px;
            border-radius: 4px;
        }
    </style>

    @stack('styles')
</head>

<body class="nx-member-body">
    <a class="nx-skip" href="#nx-main">Skip to main content</a>

    <header class="nx-header" id="nxHeader">
        <div class="nx-header__inner">
            <a href="{{ url('/') }}" class="nx-logo" aria-label="NexOra home">
                <svg viewBox="0 0 30 30" aria-hidden="true">
                    <rect x="1" y="1" width="28" height="28" rx="7" />
                    <rect class="nx-door" x="11" y="11" width="8" height="18" rx="1" />
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
                                {{ \Illuminate\Support\Str::limit($nxUser->name ?? 'Account', 14) }}
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
