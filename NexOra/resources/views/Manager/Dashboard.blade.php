@extends('layouts.dashboard')

@section('dashboard-role', 'manager')

@section('title', 'Manager Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/manager-dashboard.css') }}?v=20261124-ui1">
@endpush

@section('content')

    <div class="nx-manager-dashboard">

        {{-- PAGE HEADER --}}
        <section class="nx-page-header">

            <div class="nx-page-header__copy">
                <span class="nx-eyebrow">WORKSPACE MANAGEMENT</span>
                <h1>Manager Overview</h1>
                <p>Monitor your workspaces, bookings and business performance.</p>
            </div>

            <div class="nx-page-header__actions">

                <form method="GET" action="{{ route('manager.dashboard') }}" class="nx-date-filter">

                    @include('layouts.dashboard.partials.ui-icon', ['name' => 'calendar'])

                    <select name="period" aria-label="Filter dashboard period" onchange="this.form.submit()">

                        <option value="today" @selected($period === 'today')>
                            Today
                        </option>

                        <option value="7d" @selected($period === '7d')>
                            Last 7 Days
                        </option>

                        <option value="30d" @selected($period === '30d')>
                            Last 30 Days
                        </option>

                        <option value="90d" @selected($period === '90d')>
                            Last 90 Days
                        </option>

                        <option value="this_month" @selected($period === 'this_month')>
                            This Month
                        </option>

                    </select>
                </form>

                <a href="{{ route('manager.bookings.index') }}" class="nx-btn nx-btn--primary">

                    @include('layouts.dashboard.partials.ui-icon', ['name' => 'calendar'])
                    <span>View Bookings</span>

                </a>

            </div>

        </section>


        {{-- KPI CARDS --}}
        <section class="nx-kpi-grid">

            {{-- TOTAL WORKSPACES --}}
            <article class="nx-card nx-kpi-card">

                <div class="nx-kpi-card__top">

                    <div class="nx-kpi-card__icon">
                        @include('layouts.dashboard.partials.ui-icon', ['name' => 'briefcase'])
                    </div>

                    <span class="nx-kpi-card__label">
                        TOTAL WORKSPACES
                    </span>

                </div>

                <h2 class="nx-kpi-card__value">
                    {{ number_format($kpis['totalWorkspaces'] ?? 0) }}
                </h2>



            </article>


            {{-- TOTAL BOOKINGS --}}
            <article class="nx-card nx-kpi-card">

                <div class="nx-kpi-card__top">

                    <div class="nx-kpi-card__icon">
                        @include('layouts.dashboard.partials.ui-icon', ['name' => 'calendar-check'])
                    </div>

                    <span class="nx-kpi-card__label">
                        TOTAL BOOKINGS
                    </span>

                </div>

                <h2 class="nx-kpi-card__value">
                    {{ number_format($kpis['totalBookings'] ?? 0) }}
                </h2>



            </article>


            {{-- PENDING BOOKINGS --}}
            <article class="nx-card nx-kpi-card">

                <div class="nx-kpi-card__top">

                    <div class="nx-kpi-card__icon">
                        @include('layouts.dashboard.partials.ui-icon', ['name' => 'clock'])
                    </div>

                    <span class="nx-kpi-card__label">
                        PENDING BOOKINGS
                    </span>

                </div>

                <h2 class="nx-kpi-card__value">
                    {{ number_format($kpis['pendingBookings'] ?? 0) }}
                </h2>



            </article>


            {{-- TOTAL REVENUE --}}
            <article class="nx-card nx-kpi-card">

                <div class="nx-kpi-card__top">

                    <div class="nx-kpi-card__icon">
                        @include('layouts.dashboard.partials.ui-icon', ['name' => 'trending-up'])
                    </div>

                    <span class="nx-kpi-card__label">
                        TOTAL REVENUE
                    </span>

                </div>

                <h2 class="nx-kpi-card__value">
                    {{ number_format((float) ($kpis['totalRevenue'] ?? 0), 2) }}
                    <small>JOD</small>
                </h2>



            </article>


            {{-- AVERAGE RATING --}}
            <article class="nx-card nx-kpi-card">

                <div class="nx-kpi-card__top">

                    <div class="nx-kpi-card__icon">
                        @include('layouts.dashboard.partials.ui-icon', ['name' => 'star'])
                    </div>

                    <span class="nx-kpi-card__label">
                        AVERAGE RATING
                    </span>

                </div>

                <h2 class="nx-kpi-card__value">
                    {{ number_format((float) ($kpis['averageRating'] ?? 0), 1) }}
                    <small>/ 5</small>
                </h2>



            </article>

        </section>


        {{-- CHARTS --}}
        <section class="nx-dashboard-grid nx-dashboard-grid--charts">

            {{-- REVENUE CHART --}}
            <article class="nx-card nx-dashboard-panel nx-revenue-panel">

                <div class="nx-panel-header">

                    <div>
                        <h3>Revenue Performance</h3>
                        <p>Booking value and your net revenue over time</p>
                    </div>

                    <span class="nx-panel-tag">
                        Financial Overview
                    </span>

                </div>

                <div class="nx-chart-container">

                    <canvas id="managerRevenueChart"></canvas>

                    <div class="nx-chart-empty" id="managerRevenueEmpty">
                        @include('layouts.dashboard.partials.ui-icon', ['name' => 'bar-chart-2'])
                        <strong>No revenue data available</strong>
                        <p>Revenue statistics will appear here once data is available.</p>
                    </div>

                </div>

            </article>


            {{-- BOOKING TREND CHART --}}
            <article class="nx-card nx-dashboard-panel nx-bookings-panel">

                <div class="nx-panel-header">

                    <div>
                        <h3>Booking Trends</h3>
                        <p>Booking activity by status over time</p>
                    </div>

                    <span class="nx-panel-tag">
                        Booking Analytics
                    </span>

                </div>

                <div class="nx-chart-container">

                    <canvas id="managerBookingTrendChart"></canvas>

                    <div class="nx-chart-empty" id="managerBookingEmpty">
                        @include('layouts.dashboard.partials.ui-icon', ['name' => 'activity'])
                        <strong>No booking data available</strong>
                        <p>Booking trends will appear here once data is available.</p>
                    </div>

                </div>

            </article>

        </section>


        {{-- RECENT BOOKINGS --}}
        <section class="nx-card nx-dashboard-panel">

            <div class="nx-panel-header">

                <div>
                    <h3>Recent Bookings</h3>
                    <p>Latest bookings across your workspaces</p>
                </div>

                <a href="{{ route('manager.bookings.index') }}" class="nx-text-link">

                    View All
                    @include('layouts.dashboard.partials.ui-icon', ['name' => 'arrow-up-right'])

                </a>

            </div>


            <div class="nx-table-wrapper">

                <table class="nx-table">

                    <thead>
                        <tr>
                            <th>CUSTOMER</th>
                            <th>WORKSPACE</th>
                            <th>ROOM</th>
                            <th>DATE</th>
                            <th>AMOUNT</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($recentBookings as $booking)
                            <tr>



                                <td>
                                    {{ $booking['customerName'] ?? 'Unknown Customer' }}
                                </td>

                                <td>
                                    {{ $booking['workspaceName'] ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $booking['roomName'] ?? 'N/A' }}
                                </td>

                                <td>
                                    {{ $booking['bookingDate'] ? \Carbon\Carbon::parse($booking['bookingDate'])->format('M d, Y') : 'N/A' }}
                                </td>

                                <td>
                                    <strong>
                                        {{ number_format($booking['totalPrice'], 2) }}
                                    </strong>
                                    <small>JOD</small>
                                </td>

                                <td>
                                    <span class="nx-status nx-status--{{ strtolower($booking['status']) }}">
                                        {{ ucfirst($booking['status']) }}
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7">

                                    <div class="nx-empty-state">
                                        @include('layouts.dashboard.partials.ui-icon', [
                                            'name' => 'calendar',
                                        ])
                                        <strong>No recent bookings</strong>
                                        <p>Your latest bookings will appear here.</p>
                                    </div>

                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>


        {{-- REVIEWS AND ALERTS --}}
        <section class="nx-dashboard-grid nx-dashboard-grid--bottom">

            {{-- RECENT REVIEWS --}}
            <article class="nx-card nx-dashboard-panel">

                <div class="nx-panel-header">

                    <div>
                        <h3>Recent Reviews</h3>
                        <p>Latest customer feedback on your workspaces</p>
                    </div>

                    <span class="nx-panel-tag">
                        Customer Feedback
                    </span>

                </div>


                <div class="nx-review-list">

                    @forelse ($recentReviews as $review)
                        <div class="nx-review-item">

                            <div class="nx-review-item__top">

                                <div class="nx-review-customer">

                                    <div class="nx-review-avatar">
                                        {{ strtoupper(substr($review['customerName'] ?? 'U', 0, 1)) }}
                                    </div>

                                    <div>
                                        <strong>
                                            {{ $review['customerName'] ?? 'Unknown Customer' }}
                                        </strong>

                                        <p>
                                            {{ $review['workspaceName'] ?? 'Unknown Workspace' }}
                                        </p>
                                    </div>

                                </div>

                                <div class="nx-review-rating">
                                    @include('layouts.dashboard.partials.ui-icon', ['name' => 'star'])
                                    <strong>{{ number_format((float) $review['rating'], 1) }}</strong>
                                </div>

                            </div>

                            <p class="nx-review-comment">
                                {{ $review['comment'] ?: 'No comment provided.' }}
                            </p>

                            <time>
                                {{ $review['createdAt'] ? \Carbon\Carbon::parse($review['createdAt'])->diffForHumans() : '' }}
                            </time>

                        </div>

                    @empty

                        <div class="nx-empty-state">
                            @include('layouts.dashboard.partials.ui-icon', ['name' => 'message-square'])
                            <strong>No reviews yet</strong>
                            <p>Customer reviews will appear here.</p>
                        </div>
                    @endforelse

                </div>

            </article>


            {{-- DASHBOARD ALERTS --}}
            <article class="nx-card nx-dashboard-panel nx-alerts-panel">

                <div class="nx-panel-header">

                    <div>
                        <h3>Pending Actions</h3>
                        <p>Items that require your attention</p>
                    </div>

                    <span class="nx-panel-tag">
                        Notifications
                    </span>

                </div>


                <div class="nx-alert-list">

                    @forelse ($alerts as $alert)
                        <div class="nx-alert nx-alert--{{ $alert['type'] ?? 'warning' }}">

                            <div class="nx-alert__icon">
                                @include('layouts.dashboard.partials.ui-icon', [
                                    'name' => 'alert-triangle',
                                ])
                            </div>

                            <div class="nx-alert__content">

                                <strong>
                                    {{ $alert['title'] }}
                                </strong>

                                <p>
                                    {{ $alert['message'] }}
                                </p>

                            </div>

                            <span class="nx-alert__count">
                                {{ $alert['count'] }}
                            </span>

                        </div>

                    @empty

                        <div class="nx-alert-empty">

                            @include('layouts.dashboard.partials.ui-icon', ['name' => 'check-circle'])

                            <div>
                                <strong>All Clear</strong>
                                <p>There are no pending actions requiring your attention.</p>
                            </div>

                        </div>
                    @endforelse

                </div>

            </article>

        </section>

    </div>

@endsection


{{-- CHARTS DATA AND SCRIPTS --}}
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

    <script>
        window.managerDashboardData = {
            revenue: @json($revenueChart),
            bookingTrend: @json($bookingTrend)
        };
    </script>

    <script src="{{ asset('js/pages/manager-dashboard.js') }}?v=20261125-chart-green1"></script>
@endpush
