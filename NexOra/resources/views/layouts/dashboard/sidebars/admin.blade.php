<aside class="nx-dashboard__sidebar">

    <div class="nx-sidebar__brand">
        <a href="{{ url('/admin/dashboard') }}" class="nx-sidebar__logo">
            NexOra
        </a>
        <span class="nx-sidebar__subtitle">Admin Panel</span>
    </div>

    <nav class="nx-sidebar__nav">

        <span class="nx-sidebar__section">MAIN MENU</span>

        <a href="{{ url('/admin/dashboard') }}"
            class="nx-sidebar__link {{ request()->is('admin/dashboard') ? 'is-active' : '' }}">
            <i data-feather="grid"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ url('/admin/userindex') }}"
            class="nx-sidebar__link {{ request()->is('admin/userindex*') ? 'is-active' : '' }}">
            <i data-feather="users"></i>
            <span>Users</span>
        </a>

        <a href="{{ url('/admin/workspaces') }}"
            class="nx-sidebar__link {{ request()->is('admin/workspaces*') ? 'is-active' : '' }}">
            <i data-feather="briefcase"></i>
            <span>Workspaces</span>
        </a>

        <a href="{{ url('/admin/bookings') }}"
            class="nx-sidebar__link {{ request()->is('admin/bookings*') ? 'is-active' : '' }}">
            <i data-feather="calendar"></i>
            <span>Bookings</span>
        </a>

        <a href="{{ url('/admin/payments') }}"
            class="nx-sidebar__link {{ request()->is('admin/payments*') ? 'is-active' : '' }}">
            <i data-feather="credit-card"></i>
            <span>Payments</span>
        </a>

        <a href="{{ url('/admin/reviews') }}"
            class="nx-sidebar__link {{ request()->is('admin/reviews*') ? 'is-active' : '' }}">
            <i data-feather="star"></i>
            <span>Reviews</span>
        </a>

        @auth
    <form action="{{ route('logout') }}" method="POST">
        @csrf

        <button type="submit" class="btn btn-outline-danger">
            <i class="fas fa-sign-out-alt"></i>
            Logout
        </button>
    </form>
@endauth

    </nav>

    <div class="nx-sidebar__footer">
        <span>© {{ date('Y') }} NexOra</span>
    </div>

</aside>
