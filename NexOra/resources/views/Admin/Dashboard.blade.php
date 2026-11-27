@extends('layouts.dashboard')

@section('dashboard-role', 'admin')

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
                    @include('layouts.dashboard.partials.ui-icon', [
                        'name' => 'chevron-down',
                        'class' => 'nx-date-filter__chevron',
                    ])
                </form>

                {{-- <button type="button" class="nx-btn nx-btn--primary" disabled
                    title="Export functionality is not implemented yet">

                    @include('layouts.dashboard.partials.ui-icon', ['name' => 'download'])
                    <span>Export Report</span>
                </button> --}}

            </div>
        </section>

        <section class="nx-alert-strip" aria-labelledby="alertsTitle">
            <h2 class="nx-sr-only" id="alertsTitle">Platform alerts</h2>

            <div class="nx-alert-list">

                @forelse ($alerts as $alert)
                    @php
                        $alertLevel = in_array(
                            $alert['priority'] ?? null,
                            ['success', 'warning', 'danger', 'info'],
                            true,
                        )
                            ? $alert['priority']
                            : 'warning';

                        $alertIcons = [
                            'success' => 'check-circle',
                            'warning' => 'alert-triangle',
                            'danger' => 'x-circle',
                            'info' => 'info',
                        ];
                    @endphp

                    <div class="nx-alert nx-alert--{{ $alertLevel }}">

                        <div class="nx-alert__icon">
                            @include('layouts.dashboard.partials.ui-icon', [
                                'name' => $alertIcons[$alertLevel],
                            ])
                        </div>

                        <div class="nx-alert__content">
                            <p class="nx-alert__title">{{ $alert['title'] }}</p>
                            <p class="nx-alert__text">{{ $alert['description'] }}</p>
                        </div>

                        <span class="nx-alert__count" title="Pending items">{{ $alert['count'] }}</span>

                    </div>

                @empty

                    <div class="nx-alert-empty">
                        @include('layouts.dashboard.partials.ui-icon', ['name' => 'check-circle'])

                        <div>
                            <strong>All clear</strong>
                            <span>There are no pending actions requiring attention.</span>
                        </div>
                    </div>
                @endforelse

            </div>

        </section>

        <section class="nx-kpi-grid">

            <article class="nx-card nx-kpi-card">
                <div class="nx-kpi-card__top">
                    <div class="nx-kpi-card__icon">
                        @include('layouts.dashboard.partials.ui-icon', ['name' => 'percent'])
                    </div>
                    <span class="nx-kpi-card__label">PLATFORM COMMISSION</span>
                </div>

                <h2 class="nx-kpi-card__value">
                    {{ number_format((float) ($kpis['platformCommission'] ?? 0), 2) }}
                    <small>JOD</small>
                </h2>


            </article>


            <article class="nx-card nx-kpi-card">
                <div class="nx-kpi-card__top">
                    <div class="nx-kpi-card__icon">
                        @include('layouts.dashboard.partials.ui-icon', ['name' => 'wallet'])
                    </div>
                    <span class="nx-kpi-card__label">BOOKING GMV</span>
                </div>

                <h2 class="nx-kpi-card__value">
                    {{ number_format((float) ($kpis['bookingGMV'] ?? 0), 2) }}
                    <small>JOD</small>
                </h2>


            </article>


            <article class="nx-card nx-kpi-card">
                <div class="nx-kpi-card__top">
                    <div class="nx-kpi-card__icon">
                        @include('layouts.dashboard.partials.ui-icon', ['name' => 'calendar-check'])
                    </div>
                    <span class="nx-kpi-card__label">TOTAL BOOKINGS</span>
                </div>

                <h2 class="nx-kpi-card__value">
                    {{ number_format((int) ($kpis['totalBookings'] ?? 0)) }}
                </h2>


            </article>


            <article class="nx-card nx-kpi-card">
                <div class="nx-kpi-card__top">
                    <div class="nx-kpi-card__icon">
                        @include('layouts.dashboard.partials.ui-icon', ['name' => 'users'])
                    </div>
                    <span class="nx-kpi-card__label">TOTAL MEMBERS</span>
                </div>

                <h2 class="nx-kpi-card__value">
                    {{ number_format((int) ($kpis['totalMembers'] ?? 0)) }}
                </h2>


            </article>


            <article class="nx-card nx-kpi-card">
                <div class="nx-kpi-card__top">
                    <div class="nx-kpi-card__icon">
                        @include('layouts.dashboard.partials.ui-icon', ['name' => 'briefcase'])
                    </div>
                    <span class="nx-kpi-card__label">ACTIVE WORKSPACES</span>
                </div>

                <h2 class="nx-kpi-card__value">
                    {{ number_format((int) ($kpis['activeWorkspaces'] ?? 0)) }}
                </h2>


            </article>

        </section>


        <section class="nx-dashboard-grid nx-dashboard-grid--charts">

            <article class="nx-card nx-dashboard-panel nx-revenue-panel">

                <div class="nx-panel-header">
                    <div class="nx-panel-heading">
                        <span class="nx-panel-icon" aria-hidden="true">@include('layouts.dashboard.partials.ui-icon', ['name' => 'bar-chart-2'])</span>
                        <div>
                            <h2 class="nx-panel-title" id="revenueTitle">Revenue Performance</h2>
                            <p class="nx-panel-subtitle">Booking value and platform commission over time</p>
                        </div>
                    </div>
                </div>

                <div class="nx-chart-container">
                    <canvas id="revenueChart"></canvas>

                    <div class="nx-chart-empty" id="revenueEmpty">
                        @include('layouts.dashboard.partials.ui-icon', ['name' => 'bar-chart-2'])
                        <strong>No revenue data available</strong>
                        <p>Revenue statistics will appear here once data is available.</p>
                    </div>
                </div>

            </article>


            <article class="nx-card nx-dashboard-panel nx-bookings-panel">

                <div class="nx-panel-header">
                    <div class="nx-panel-heading">
                        <span class="nx-panel-icon" aria-hidden="true">@include('layouts.dashboard.partials.ui-icon', ['name' => 'pie-chart'])</span>
                        <div>
                            <h2 class="nx-panel-title" id="bookingTitle">Booking Overview</h2>
                            <p class="nx-panel-subtitle">Booking status distribution</p>
                        </div>
                    </div>
                </div>

                <div class="nx-donut-wrapper">
                    <canvas id="bookingChart"></canvas>

                    <div class="nx-chart-empty" id="bookingEmpty">
                        @include('layouts.dashboard.partials.ui-icon', ['name' => 'pie-chart'])
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
                    <div class="nx-panel-heading">
                        <span class="nx-panel-icon" aria-hidden="true">@include('layouts.dashboard.partials.ui-icon', ['name' => 'briefcase'])</span>
                        <div>
                            <h2 class="nx-panel-title" id="topWorkspacesTitle">Top Performing Workspaces</h2>
                            <p class="nx-panel-subtitle">Workspaces ranked by booking activity</p>
                        </div>
                    </div>

                    <a href="{{ url('/admin/workspaces') }}" class="nx-text-link">
                        View All
                        @include('layouts.dashboard.partials.ui-icon', ['name' => 'arrow-up-right'])
                    </a>
                </div>

                <div class="nx-table-wrapper">

                    <table class="nx-table">

                        <thead>
                            <tr>
                                <th>WORKSPACE</th>

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
                                        <div class="nx-entity">
                                            <span class="nx-avatar nx-avatar--sm"
                                                aria-hidden="true">{{ mb_strtoupper(mb_substr(trim($workspace['name']), 0, 1)) }}</span>
                                            <strong class="nx-table-primary" title="{{ $workspace['name'] }}">
                                                {{ $workspace['name'] }}
                                            </strong>
                                        </div>
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
                                            @include('layouts.dashboard.partials.ui-icon', [
                                                'name' => 'inbox',
                                            ])
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
                    <div class="nx-panel-heading">
                        <span class="nx-panel-icon" aria-hidden="true">@include('layouts.dashboard.partials.ui-icon', ['name' => 'activity'])</span>
                        <div>
                            <h2 class="nx-panel-title" id="recentActivityTitle">Recent Activity</h2>
                            <p class="nx-panel-subtitle">Latest platform activities</p>
                        </div>
                    </div>
                </div>

                <div class="nx-activity-list" tabindex="0" role="region" aria-labelledby="recentActivityTitle">

                    @forelse ($recentActivities as $activity)
                        <div class="nx-activity-item">

                            <div class="nx-activity-icon nx-activity-icon--{{ $activity['type'] }}">
                                @switch($activity['type'])
                                    @case('booking')
                                        @include('layouts.dashboard.partials.ui-icon', [
                                            'name' => 'calendar',
                                        ])
                                    @break

                                    @case('payment')
                                        @include('layouts.dashboard.partials.ui-icon', [
                                            'name' => 'credit-card',
                                        ])
                                    @break

                                    @case('user')
                                        @include('layouts.dashboard.partials.ui-icon', ['name' => 'user'])
                                    @break

                                    @case('workspace')
                                        @include('layouts.dashboard.partials.ui-icon', [
                                            'name' => 'briefcase',
                                        ])
                                    @break

                                    @default
                                        @include('layouts.dashboard.partials.ui-icon', [
                                            'name' => 'activity',
                                        ])
                                @endswitch
                            </div>

                            <div class="nx-activity-content">
                                <strong>{{ $activity['title'] }}</strong>
                            </div>

                            <time datetime="{{ $activity['createdAt'] }}">
                                {{ \Carbon\Carbon::parse($activity['createdAt'])->diffForHumans() }}
                            </time>

                        </div>


                        @empty

                            <div class="nx-empty-state">
                                @include('layouts.dashboard.partials.ui-icon', ['name' => 'clock'])
                                <strong>No recent activity</strong>
                                <p>Platform activity will appear here.</p>
                            </div>
                        @endforelse

                    </div>

                </article>

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
