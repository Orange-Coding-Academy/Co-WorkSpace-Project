@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/admin-dashboard.css') }}">
@endpush

@section('content')

    <div class="nx-admin-dashboard">

        <section class="nx-page-header">
            <div class="nx-page-header__copy">
                <span class="nx-eyebrow">ADMINISTRATION</span>
                <h1>Platform Overview</h1>
                <p>Monitor your platform performance and business activity.</p>
            </div>

            <div class="nx-page-header__actions">

                <form method="GET" action="{{ route('admin.AdminDashboard') }}" class="nx-date-filter">

                    <i data-feather="calendar"></i>

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

                <button type="button" class="nx-btn nx-btn--primary" disabled
                    title="Export functionality is not implemented yet">

                    <i data-feather="download"></i>
                    <span>Export Report</span>
                </button>

            </div>
        </section>


        <section class="nx-kpi-grid">

            <article class="nx-card nx-kpi-card">
                <div class="nx-kpi-card__top">
                    <div class="nx-kpi-card__icon">
                        <i data-feather="trending-up"></i>
                    </div>
                    <span class="nx-kpi-card__label">PLATFORM COMMISSION</span>
                </div>

                <h2 class="nx-kpi-card__value">
                    {{ number_format((float) ($kpis['platformCommission'] ?? 0), 2) }}
                    <small>JOD</small>
                </h2>

                <p class="nx-kpi-card__description">
                    Platform earnings from bookings
                </p>
            </article>


            <article class="nx-card nx-kpi-card">
                <div class="nx-kpi-card__top">
                    <div class="nx-kpi-card__icon">
                        <i data-feather="wallet"></i>
                    </div>
                    <span class="nx-kpi-card__label">BOOKING GMV</span>
                </div>

                <h2 class="nx-kpi-card__value">
                    {{ number_format((float) ($kpis['bookingGMV'] ?? 0), 2) }}
                    <small>JOD</small>
                </h2>

                <p class="nx-kpi-card__description">
                    Total eligible booking value
                </p>
            </article>


            <article class="nx-card nx-kpi-card">
                <div class="nx-kpi-card__top">
                    <div class="nx-kpi-card__icon">
                        <i data-feather="calendar-check"></i>
                    </div>
                    <span class="nx-kpi-card__label">TOTAL BOOKINGS</span>
                </div>

                <h2 class="nx-kpi-card__value">
                    {{ number_format((int) ($kpis['totalBookings'] ?? 0)) }}
                </h2>

                <p class="nx-kpi-card__description">
                    Bookings created during the period
                </p>
            </article>


            <article class="nx-card nx-kpi-card">
                <div class="nx-kpi-card__top">
                    <div class="nx-kpi-card__icon">
                        <i data-feather="users"></i>
                    </div>
                    <span class="nx-kpi-card__label">TOTAL MEMBERS</span>
                </div>

                <h2 class="nx-kpi-card__value">
                    {{ number_format((int) ($kpis['totalMembers'] ?? 0)) }}
                </h2>

                <p class="nx-kpi-card__description">
                    Registered members
                </p>
            </article>


            <article class="nx-card nx-kpi-card">
                <div class="nx-kpi-card__top">
                    <div class="nx-kpi-card__icon">
                        <i data-feather="briefcase"></i>
                    </div>
                    <span class="nx-kpi-card__label">ACTIVE WORKSPACES</span>
                </div>

                <h2 class="nx-kpi-card__value">
                    {{ number_format((int) ($kpis['activeWorkspaces'] ?? 0)) }}
                </h2>

                <p class="nx-kpi-card__description">
                    Approved and bookable spaces
                </p>
            </article>

        </section>


        <section class="nx-dashboard-grid nx-dashboard-grid--charts">

            <article class="nx-card nx-dashboard-panel nx-revenue-panel">

                <div class="nx-panel-header">
                    <div>
                        <h3>Revenue Performance</h3>
                        <p>Booking value and platform commission over time</p>
                    </div>

                    <span class="nx-panel-tag">
                        Financial Overview
                    </span>
                </div>

                <div class="nx-chart-container">
                    <canvas id="revenueChart"></canvas>

                    <div class="nx-chart-empty" id="revenueEmpty">
                        <i data-feather="bar-chart-2"></i>
                        <strong>No revenue data available</strong>
                        <p>Revenue statistics will appear here once data is available.</p>
                    </div>
                </div>

            </article>


            <article class="nx-card nx-dashboard-panel nx-bookings-panel">

                <div class="nx-panel-header">
                    <div>
                        <h3>Booking Overview</h3>
                        <p>Booking status distribution</p>
                    </div>

                    <span class="nx-panel-tag">
                        Bookings
                    </span>
                </div>

                <div class="nx-donut-wrapper">
                    <canvas id="bookingChart"></canvas>

                    <div class="nx-chart-empty" id="bookingEmpty">
                        <i data-feather="pie-chart"></i>
                        <strong>No booking data available</strong>
                        <p>Booking statistics will appear here.</p>
                    </div>
                </div>

                <div class="nx-chart-legend" id="bookingLegend"></div>

            </article>

        </section>


        {{-- WORKSPACES AND ACTIVITIES --}}
        <section class="nx-dashboard-grid nx-dashboard-grid--bottom">

            {{-- TOP WORKSPACES --}}
            <article class="nx-card nx-dashboard-panel">

                <div class="nx-panel-header">
                    <div>
                        <h3>Top Performing Workspaces</h3>
                        <p>Workspaces ranked by booking activity</p>
                    </div>

                    <a href="{{ url('/admin/workspaces') }}" class="nx-text-link">
                        View All
                        <i data-feather="arrow-up-right"></i>
                    </a>
                </div>

                <div class="nx-table-wrapper">

                    <table class="nx-table">

                        <thead>
                            <tr>
                                <th>WORKSPACE</th>
                                <th>BOOKINGS</th>
                                <th>GMV</th>
                                <th>PERFORMANCE</th>
                            </tr>
                        </thead>

                        <tbody>

                            @php
                                $maxBookings = max(collect($topWorkspaces)->max('totalBookings') ?? 0, 1);
                            @endphp

                            @forelse ($topWorkspaces as $workspace)
                                @php
                                    $performance = ($workspace['totalBookings'] / $maxBookings) * 100;
                                @endphp

                                <tr>
                                    <td>
                                        <strong class="nx-table-primary">
                                            {{ $workspace['name'] }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ number_format($workspace['totalBookings']) }}
                                    </td>

                                    <td>
                                        {{ number_format($workspace['totalGMV'], 2) }}
                                        <small>JOD</small>
                                    </td>

                                    <td>
                                        <div class="nx-performance">
                                            <div class="nx-progress">
                                                <div class="nx-progress__bar" style="width: {{ $performance }}%">
                                                </div>
                                            </div>

                                            <span>{{ round($performance) }}%</span>
                                        </div>
                                    </td>
                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4">
                                        <div class="nx-empty-state">
                                            <i data-feather="inbox"></i>
                                            <strong>No workspace data available</strong>
                                            <p>Workspace performance will appear here.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </article>


            {{-- RECENT ACTIVITIES --}}
            <article class="nx-card nx-dashboard-panel">

                <div class="nx-panel-header">
                    <div>
                        <h3>Recent Activity</h3>
                        <p>Latest platform activities</p>
                    </div>

                    <span class="nx-panel-tag">
                        Latest
                    </span>
                </div>

                <div class="nx-activity-list">

                    @forelse ($recentActivities as $activity)
                        <div class="nx-activity-item">

                            <div class="nx-activity-icon nx-activity-icon--{{ $activity['type'] }}">

                                @switch($activity['type'])
                                    @case('booking')
                                        <i data-feather="calendar"></i>
                                    @break

                                    @case('payment')
                                        <i data-feather="credit-card"></i>
                                    @break

                                    @case('user')
                                        <i data-feather="user"></i>
                                    @break

                                    @case('workspace')
                                        <i data-feather="briefcase"></i>
                                    @break

                                    @default
                                        <i data-feather="activity"></i>
                                @endswitch

                            </div>

                            <div class="nx-activity-content">
                                <strong>{{ $activity['title'] }}</strong>
                                <p>{{ $activity['description'] }}</p>
                            </div>

                            <time datetime="{{ $activity['createdAt'] }}">
                                {{ \Carbon\Carbon::parse($activity['createdAt'])->diffForHumans() }}
                            </time>

                        </div>

                        @empty

                            <div class="nx-empty-state">
                                <i data-feather="clock"></i>
                                <strong>No recent activity</strong>
                                <p>Platform activity will appear here.</p>
                            </div>
                        @endforelse

                    </div>

                </article>

            </section>


            {{-- ALERTS --}}
            <section class="nx-card nx-dashboard-panel nx-alerts-panel">

                <div class="nx-panel-header">
                    <div>
                        <h3>Platform Alerts</h3>
                        <p>Actions that may require your attention</p>
                    </div>

                    <span class="nx-panel-tag">
                        Notifications
                    </span>
                </div>

                <div class="nx-alert-list">

                    @forelse ($alerts as $alert)
                        <div class="nx-alert nx-alert--{{ $alert['priority'] ?? 'warning' }}">

                            <div class="nx-alert__icon">
                                <i data-feather="alert-triangle"></i>
                            </div>

                            <div class="nx-alert__content">
                                <strong>{{ $alert['title'] }}</strong>
                                <p>{{ $alert['description'] }}</p>
                            </div>

                            <span class="nx-alert__count">
                                {{ $alert['count'] }}
                            </span>

                        </div>

                    @empty

                        <div class="nx-alert-empty">
                            <i data-feather="check-circle"></i>

                            <div>
                                <strong>All Clear</strong>
                                <p>There are no pending actions requiring attention.</p>
                            </div>
                        </div>
                    @endforelse

                </div>

            </section>

        </div>

    @endsection


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>

        <script>
            window.adminDashboardData = {
                revenue: @json($revenueChart),
                bookingStatuses: @json($bookingStatuses),
            };
        </script>

        <script src="{{ asset('js/pages/admin-dashboard.js') }}"></script>
    @endpush
