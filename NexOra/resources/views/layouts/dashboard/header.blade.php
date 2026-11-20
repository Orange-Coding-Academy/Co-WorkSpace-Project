
<header class="nx-topbar">

    <div class="nx-topbar__left">
        <button type="button" class="nx-icon-button" id="sidebarToggle"
                aria-label="Toggle sidebar">
            <i data-feather="menu"></i>
        </button>

        <div class="nx-topbar__breadcrumb">
            <span>
                {{ request()->routeIs('manager.*') ? 'Manager' : 'Admin' }}
            </span>

            <i data-feather="chevron-right"></i>

            <strong>@yield('page-title', 'Dashboard')</strong>
        </div>
    </div>

    <div class="nx-topbar__right">

        <div class="nx-topbar__user">
            <div class="nx-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
            </div>

            <div class="nx-topbar__user-info">
                <strong>{{ auth()->user()->name ?? 'User' }}</strong>

                <span>
                    {{ request()->routeIs('manager.*') ? 'Workspace Manager' : 'Administrator' }}
                </span>
            </div>
        </div>

    </div>

</header>
