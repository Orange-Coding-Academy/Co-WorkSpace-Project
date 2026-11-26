@php
    $nxManagerSections = [
        'MAIN MENU' => [
            ['manager.dashboard', [], ['manager.dashboard'], 'Dashboard', 'grid'],
        ],
        'MANAGEMENT' => [
            ['manager.ai.index', [], ['manager.ai.*'], 'AI Assistant', 'cpu'],
            ['manager.workspaces.create', ['view' => 'list'], ['manager.workspaces.*', 'manager.rooms.*'], 'Workspaces', 'briefcase'],
            ['manager.bookings.index', [], ['manager.bookings.*'], 'Bookings', 'calendar'],
            ['manager.membership-plans.index', [], ['manager.membership-plans.*'], 'Membership Plans', 'layers'],
            ['manager.reviews.index', [], ['manager.reviews.*'], 'Reviews', 'star'],
        ],
        'ACCOUNT' => [
            ['manager.profile.index', [], ['manager.profile.*'], 'My Profile', 'user'],
        ],
    ];
@endphp
<aside class="nx-dashboard__sidebar" id="nxSidebar">

    {{-- BRAND --}}
    <div class="nx-sidebar__brand">
        @if (Route::has('manager.dashboard'))
            <a href="{{ route('manager.dashboard') }}" class="nx-sidebar__logo">
                <svg class="nx-brand-mark" viewBox="0 0 40 40" aria-hidden="true">
                    <rect x="1" y="1" width="38" height="38" rx="11" />
                    <path class="nx-brand-mark__glyph" d="M12 29V11l16 18V11" />
                </svg>
                NexOra
            </a>
        @else
            <span class="nx-sidebar__logo">
                <svg class="nx-brand-mark" viewBox="0 0 40 40" aria-hidden="true">
                    <rect x="1" y="1" width="38" height="38" rx="11" />
                    <path class="nx-brand-mark__glyph" d="M12 29V11l16 18V11" />
                </svg>
                NexOra
            </span>
        @endif
        <span class="nx-sidebar__subtitle">Manager Panel</span>
    </div>

    {{-- NAVIGATION --}}
    <nav class="nx-sidebar__nav" aria-label="Manager navigation">
        @foreach ($nxManagerSections as $nxSection => $nxLinks)
            <span class="nx-sidebar__section">{{ $nxSection }}</span>

            @foreach ($nxLinks as [$nxName, $nxParams, $nxPatterns, $nxLabel, $nxIcon])
                @if (Route::has($nxName))
                    @php $nxActive = request()->routeIs(...$nxPatterns); @endphp
                    <a href="{{ route($nxName, $nxParams) }}"
                        class="nx-sidebar__link {{ $nxActive ? 'is-active' : '' }}"
                        @if ($nxActive) aria-current="page" @endif>
                        @include('layouts.dashboard.partials.icon', ['name' => $nxIcon])
                        <span>{{ $nxLabel }}</span>
                    </a>
                @endif
            @endforeach
        @endforeach
         @auth
            @if (Route::has('logout'))
                <form method="POST" action="{{ route('logout') }}" class="nx-sidebar__logout">
                    @csrf
                    <button type="submit" class="nx-sidebar__logout-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" /><path d="m16 17 5-5-5-5" /><path d="M21 12H9" />
                        </svg>
                        <span>Log out</span>
                    </button>
                </form>
            @endif
        @endauth
    </nav>

    {{-- FOOTER --}}
    <div class="nx-sidebar__footer">
        <span>© {{ date('Y') }} NexOra</span>
    </div>

</aside>
