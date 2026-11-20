<aside class="nx-dashboard__sidebar">

    {{-- BRAND --}}
    <div class="nx-sidebar__brand">
        <a href="{{ route('manager.dashboard') }}" class="nx-sidebar__logo">
            NexOra
        </a>
        <span class="nx-sidebar__subtitle">Manager Panel</span>
    </div>

    {{-- NAVIGATION --}}
    <nav class="nx-sidebar__nav">

        <span class="nx-sidebar__section">MAIN MENU</span>

        {{-- Dashboard --}}
        <a href="{{ route('manager.dashboard') }}"
            class="nx-sidebar__link {{ request()->routeIs('manager.dashboard') ? 'is-active' : '' }}">
            <i data-feather="grid"></i>
            <span>Dashboard</span>
        </a>

        {{-- AI Assistant --}}
        <span class="nx-sidebar__section">MANAGEMENT</span>

        <a href="{{ route('manager.ai.index') }}"
            class="nx-sidebar__link {{ request()->routeIs('manager.ai.*') ? 'is-active' : '' }}">
            <i data-feather="cpu"></i>
            <span>AI Assistant</span>
        </a>

        {{-- Workspaces --}}
        <a href="{{ route('manager.workspaces.create') }}"
            class="nx-sidebar__link {{ request()->routeIs('manager.workspaces.*') || request()->routeIs('manager.rooms.*') ? 'is-active' : '' }}">
            <i data-feather="briefcase"></i>
            <span>Workspaces</span>
        </a>

        {{-- Bookings --}}
        <a href="{{ route('manager.bookings.index') }}"
            class="nx-sidebar__link {{ request()->routeIs('manager.bookings.*') ? 'is-active' : '' }}">
            <i data-feather="calendar"></i>
            <span>Bookings</span>
        </a>

        {{-- Membership Plans --}}
        <a href="{{ route('manager.membership-plans.index') }}"
            class="nx-sidebar__link {{ request()->routeIs('manager.membership-plans.*') ? 'is-active' : '' }}">
            <i data-feather="layers"></i>
            <span>Membership Plans</span>
        </a>

        {{-- Reviews --}}
        <a href="{{ route('manager.reviews.index') }}"
            class="nx-sidebar__link {{ request()->routeIs('manager.reviews.*') ? 'is-active' : '' }}">
            <i data-feather="star"></i>
            <span>Reviews</span>
        </a>

        {{-- ACCOUNT --}}
        <span class="nx-sidebar__section">ACCOUNT</span>

        <a href="{{ route('manager.profile.index') }}"
            class="nx-sidebar__link {{ request()->routeIs('manager.profile.*') ? 'is-active' : '' }}">
            <i data-feather="user"></i>
            <span>My Profile</span>
        </a>

    </nav>

    {{-- FOOTER --}}
    <div class="nx-sidebar__footer">
        <span>© {{ date('Y') }} NexOra</span>
    </div>

</aside>
