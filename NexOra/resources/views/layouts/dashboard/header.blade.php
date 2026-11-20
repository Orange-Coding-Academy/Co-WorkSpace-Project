<header class="nx-topbar">

    <div class="nx-topbar__left">
        <button type="button" class="nx-icon-button" id="sidebarToggle" aria-label="Toggle sidebar">
            <i data-feather="menu"></i>
        </button>

        <div class="nx-topbar__breadcrumb">
            <span>Admin</span>
            <i data-feather="chevron-right"></i>
            <strong>@yield('page-title', 'Dashboard')</strong>
        </div>
    </div>

    <div class="nx-topbar__right">

        <div class="nx-topbar__user">
            <div class="nx-avatar">
                {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
            </div>

            <div class="nx-topbar__user-info">
                <strong>{{ auth()->user()->name ?? 'Administrator' }}</strong>
                <span>Administrator</span>
            </div>
        </div>

    </div>

</header>
