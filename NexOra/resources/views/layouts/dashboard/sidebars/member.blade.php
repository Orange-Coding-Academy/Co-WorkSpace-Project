@php
    $nxMemberSections = [
        'DISCOVER' => [
            ['explore', ['explore'], 'Explore Workspaces', 'compass'],
            ['member.workspace-ai.index', ['member.workspace-ai.*'], 'Workspace Finder', 'cpu'],
        ],
        'MY ACCOUNT' => [
            ['member.bookings.index', ['member.bookings.*', 'member.checkout.*'], 'My Bookings', 'calendar'],
            ['member.memberships.index', ['member.memberships.*'], 'My Memberships', 'layers'],
            ['member.reviews.index', ['member.reviews.*'], 'My Reviews', 'star'],
            ['member.profile.index', ['member.profile.*'], 'My Profile', 'user'],
        ],
    ];
@endphp
<aside class="nx-dashboard__sidebar" id="nxSidebar">

    <div class="nx-sidebar__brand">
        @if (Route::has('explore'))
            <a href="{{ route('explore') }}" class="nx-sidebar__logo">NexOra</a>
        @else
            <span class="nx-sidebar__logo">NexOra</span>
        @endif
        <span class="nx-sidebar__subtitle">Member Space</span>
    </div>

    <nav class="nx-sidebar__nav" aria-label="Member navigation">
        @foreach ($nxMemberSections as $nxSection => $nxLinks)
            <span class="nx-sidebar__section">{{ $nxSection }}</span>

            @foreach ($nxLinks as [$nxName, $nxPatterns, $nxLabel, $nxIcon])
                @if (Route::has($nxName))
                    @php $nxActive = request()->routeIs(...$nxPatterns); @endphp
                    <a href="{{ route($nxName) }}"
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
                <form method="POST" action="{{ route('logout') }}" class="nx-topbar__logout">
                    @csrf
                    <button type="submit" class="nx-topbar__logout-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" /><path d="m16 17 5-5-5-5" /><path d="M21 12H9" />
                        </svg>
                        <span>Log out</span>
                    </button>
                </form>
            @endif
        @endauth
    </nav>

    <div class="nx-sidebar__footer">
        <span>© {{ date('Y') }} NexOra</span>
    </div>

</aside>
