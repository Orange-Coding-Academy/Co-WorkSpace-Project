@php
    $nxSolid = $nxSolid ?? false; 
    $nxHome = $nxSolid ? url('/') : ''; 
    $nxUser = auth()->user();
    $nxIsMember = $nxUser && $nxUser->role === 'member';
@endphp

<header class="nx-site-header {{ $nxSolid ? 'is-solid is-forced' : '' }}" id="siteHeader">
    <div class="nx-wrap nx-site-header__inner">
        <a href="{{ url('/') }}" class="nx-logo" aria-label="NexOra home">
            <svg class="nx-brand-mark" viewBox="0 0 40 40" aria-hidden="true">
                <rect x="1" y="1" width="38" height="38" rx="11" />
                <path class="nx-brand-mark__glyph" d="M12 29V11l16 18V11" />
            </svg>
            NexOra
        </a>

        <nav class="nx-nav" aria-label="Primary">
            <ul class="nx-nav__list">
                @guest
                    <li><a class="nx-nav__link" href="{{ $nxHome }}#discover">Discover</a></li>
                    <li><a class="nx-nav__link" href="{{ $nxHome }}#managers">For managers</a></li>
                    <li><a class="nx-nav__link" href="{{ $nxHome }}#how">How it works</a></li>
                    <li><a class="nx-nav__link" href="{{ $nxHome }}#membership">Membership</a></li>
                @endguest

                @if ($nxIsMember)
                    <li><a class="nx-nav__link {{ request()->routeIs('explore') ? 'is-active' : '' }}"
                            href="{{ route('explore') }}">Explore</a></li>
                    @if (Route::has('member.bookings.index'))
                        <li><a class="nx-nav__link {{ request()->routeIs('member.bookings.*') ? 'is-active' : '' }}"
                                href="{{ route('member.bookings.index') }}">My Bookings</a></li>
                    @endif
                    @if (Route::has('member.memberships.index'))
                        <li><a class="nx-nav__link {{ request()->routeIs('member.memberships.*') ? 'is-active' : '' }}"
                                href="{{ route('member.memberships.index') }}">My Memberships</a></li>
                    @endif
                @endif
            </ul>
        </nav>

        <div class="nx-actions">
            @guest
                <a class="nx-signin" href="{{ route('login') }}">Sign in</a>
                <a class="nx-btn nx-btn--accent" href="{{ route('register') }}">Create account</a>
            @endguest

            @auth
                @if ($nxIsMember)
                    <details class="nx-account">
                        <summary>
                            <span class="nx-avatar">{{ strtoupper(substr($nxUser->name, 0, 1)) }}</span>
                            <span class="nx-account__name">{{ $nxUser->name }}</span>
                        </summary>
                        <div class="nx-account__menu">
                            @if (Route::has('member.workspace-ai.index'))
                                <a href="{{ route('member.workspace-ai.index') }}">Workspace Finder</a>
                            @endif
                            @if (Route::has('member.reviews.index'))
                                <a href="{{ route('member.reviews.index') }}">My Reviews</a>
                            @endif
                            @if (Route::has('member.profile.index'))
                                <a href="{{ route('member.profile.index') }}">My Profile</a>
                            @endif
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">Log out</button>
                            </form>
                        </div>
                    </details>
                @else
                    <a class="nx-btn nx-btn--accent"
                        href="{{ $nxUser->role === 'admin' ? route('admin.AdminDashboard') : route('manager.dashboard') }}">
                        Dashboard
                    </a>
                @endif
            @endauth

            <button type="button" class="nx-burger" id="menuToggle" aria-expanded="false" aria-label="Open menu">
                <span></span><span></span><span></span>
            </button>
        </div>
    </div>
</header>
