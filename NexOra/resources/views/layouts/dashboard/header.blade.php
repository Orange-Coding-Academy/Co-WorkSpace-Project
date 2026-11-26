
@php
    $nxRole = $nxRole ?? 'admin';

    $nxRoleLabels = [
        'admin' => 'Administrator',
        'manager' => 'Workspace Manager',
        'member' => 'Member',
    ];

    $nxAreaLabels = [
        'admin' => 'Admin',
        'manager' => 'Manager',
        'member' => 'My Account',
    ];

    $nxProfileRoutes = [
        'admin' => 'admin.profile.index',
        'manager' => 'manager.profile.index',
        'member' => 'member.profile.index',
    ];

    $nxUser = auth()->user();

    $nxUserName = $nxUser?->name ?? $nxRoleLabels[$nxRole] ?? 'User';
    $nxUserAvatar = $nxUser?->avatar;
    $nxUserInitial = strtoupper(substr($nxUserName, 0, 1));

    $nxProfileRoute = $nxProfileRoutes[$nxRole] ?? null;
    $nxHasProfileRoute = $nxProfileRoute && Route::has($nxProfileRoute);
@endphp

<header class="nx-topbar">

    <div class="nx-topbar__left">

        <button
            type="button"
            class="nx-icon-button"
            id="sidebarToggle"
            aria-label="Toggle navigation"
            aria-controls="nxSidebar"
            aria-expanded="true"
        >
            <svg viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true" focusable="false">
                <path d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <nav class="nx-topbar__breadcrumb" aria-label="Breadcrumb">
            <span>{{ $nxAreaLabels[$nxRole] ?? 'Dashboard' }}</span>

            <svg viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round"
                aria-hidden="true" focusable="false">
                <path d="m9 18 6-6-6-6" />
            </svg>

            <strong aria-current="page">
                @yield('page-title', 'Dashboard')
            </strong>
        </nav>

    </div>

    <div class="nx-topbar__right">

        @if (Route::has('explore'))
            <a href="{{ route('explore') }}" class="nx-topbar__explore">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round"
                    aria-hidden="true" focusable="false">
                    <circle cx="11" cy="11" r="7" />
                    <path d="m20 20-3.5-3.5" />
                </svg>

                <span>Explore Workspaces</span>
            </a>
        @endif

        @if ($nxHasProfileRoute)
            <a
                href="{{ route($nxProfileRoute) }}"
                class="nx-topbar__user nx-topbar__user--link"
                aria-label="My profile: {{ $nxUserName }}"
            >
        @else
            <div class="nx-topbar__user">
        @endif

                <div class="nx-avatar" aria-hidden="true">
                    @if ($nxUserAvatar)
                        <img
                            src="{{ asset('storage/' . $nxUserAvatar) }}"
                            alt=""
                        >
                    @else
                        {{ $nxUserInitial }}
                    @endif
                </div>

                <div class="nx-topbar__user-info">
                    <strong>{{ $nxUserName }}</strong>
                    <span>{{ $nxRoleLabels[$nxRole] ?? 'User' }}</span>
                </div>

        @if ($nxHasProfileRoute)
                <svg class="nx-topbar__user-chevron" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round" aria-hidden="true" focusable="false">
                    <path d="m9 18 6-6-6-6" />
                </svg>
            </a>
        @else
            </div>
        @endif

    </div>

</header>
