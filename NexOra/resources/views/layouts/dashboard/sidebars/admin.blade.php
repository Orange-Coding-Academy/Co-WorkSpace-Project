@php
    // name => [route name (used only when registered), existing fallback path, active path pattern, label]
    $nxAdminLinks = [
        ['admin.AdminDashboard', '/admin/dashboard', 'admin/dashboard', 'Dashboard', 'grid'],
        ['admin.users.index', '/admin/userindex', 'admin/userindex*', 'Users', 'users'],
        ['admin.workspaces', '/admin/workspaces', 'admin/workspaces*', 'Workspaces', 'briefcase'],
        [null, '/admin/bookings', 'admin/bookings*', 'Bookings', 'calendar'],
        ['admin.payments.index', '/admin/payments', 'admin/payments*', 'Payments', 'credit-card'],
        ['admin.reviews.index', '/admin/reviews', 'admin/reviews*', 'Reviews', 'star'],
        ['admin.profile.index', '/admin/profile', 'admin/profile*', 'Profile', 'user'],
    ];
    $nxHomeUrl = Route::has('admin.AdminDashboard') ? route('admin.AdminDashboard') : url('/admin/dashboard');
@endphp
<aside class="nx-dashboard__sidebar" id="nxSidebar">

    <div class="nx-sidebar__brand">
        <a href="{{ $nxHomeUrl }}" class="nx-sidebar__logo">
            NexOra
        </a>
        <span class="nx-sidebar__subtitle">Admin Panel</span>
    </div>

    <nav class="nx-sidebar__nav" aria-label="Admin navigation">

        <span class="nx-sidebar__section">MAIN MENU</span>

        @foreach ($nxAdminLinks as [$nxName, $nxPath, $nxPattern, $nxLabel, $nxIcon])
            @php
                $nxHasRoute = $nxName && Route::has($nxName);
                $nxActive = request()->is($nxPattern) || ($nxHasRoute && request()->routeIs($nxName));
            @endphp
            <a href="{{ $nxHasRoute ? route($nxName) : url($nxPath) }}"
                class="nx-sidebar__link {{ $nxActive ? 'is-active' : '' }}"
                @if ($nxActive) aria-current="page" @endif>
                @include('layouts.dashboard.partials.icon', ['name' => $nxIcon])
                <span>{{ $nxLabel }}</span>
            </a>
        @endforeach
        @auth
            @if (Route::has('logout'))
                <form method="POST" action="{{ route('logout') }}" class="nx-sidebar__logout">
                    @csrf
                    <button type="submit" class="nx-sidebar__logout-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <path d="m16 17 5-5-5-5" />
                            <path d="M21 12H9" />
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
