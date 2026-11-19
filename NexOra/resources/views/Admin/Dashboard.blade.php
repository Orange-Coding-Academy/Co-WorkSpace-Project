<main class="cw-dashboard">

    <div class="cw-page-header">
        <div>
            <span class="cw-eyebrow">ADMINISTRATION</span>
            <h1>Platform Overview</h1>
            <p>Monitor your platform performance and business activity.</p>
        </div>

        <div class="cw-header-actions">
            <span class="cw-date-filter">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                    <rect x="3" y="5" width="18" height="16" rx="2" />
                    <path d="M16 3v4M8 3v4M3 10h18" />
                </svg>
                Last 30 Days
            </span>
            <button class="cw-export-btn" type="button" disabled>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                    <path d="M12 3v12m-5-5 5 5 5-5" />
                    <path d="M5 17v4h14v-4" />
                </svg>
                Export Report
            </button>
        </div>
    </div>

    {{-- KPI CARDS --}}
    <section class="cw-kpi-grid">

        <div class="cw-kpi-card">
            <div class="cw-kpi-top">
                <span class="cw-kpi-icon green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 2v20m5-15H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                    </svg>
                </span>
                <span class="cw-kpi-label">PLATFORM COMMISSION</span>
            </div>
            <h2>
                {{ isset($kpis['platformCommission']) ? number_format((float) $kpis['platformCommission'], 2) : '—' }}
                <small>JOD</small>
            </h2>
            <span class="cw-kpi-description">Platform earnings from bookings</span>
        </div>

        <div class="cw-kpi-card">
            <div class="cw-kpi-top">
                <span class="cw-kpi-icon blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <path d="M3 10h18M7 15h3" />
                    </svg>
                </span>
                <span class="cw-kpi-label">BOOKING GMV</span>
            </div>
            <h2>
                {{ isset($kpis['bookingGMV']) ? number_format((float) $kpis['bookingGMV'], 2) : '—' }}
                <small>JOD</small>
            </h2>
            <span class="cw-kpi-description">Total eligible booking value</span>
        </div>

        <div class="cw-kpi-card">
            <div class="cw-kpi-top">
                <span class="cw-kpi-icon purple">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="4" width="18" height="17" rx="2" />
                        <path d="M16 2v4M8 2v4M3 9h18m-13 5 2 2 4-4" />
                    </svg>
                </span>
                <span class="cw-kpi-label">TOTAL BOOKINGS</span>
            </div>
            <h2>
                {{ isset($kpis['totalBookings']) ? number_format($kpis['totalBookings']) : '—' }}
            </h2>
            <span class="cw-kpi-description">Bookings created during the period</span>
        </div>

        <div class="cw-kpi-card">
            <div class="cw-kpi-top">
                <span class="cw-kpi-icon orange">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="9" cy="8" r="4" />
                        <path d="M2 21v-2a7 7 0 0 1 14 0v2M16 4a4 4 0 0 1 0 8m2 3a6 6 0 0 1 4 6" />
                    </svg>
                </span>
                <span class="cw-kpi-label">TOTAL MEMBERS</span>
            </div>
            <h2>
                {{ isset($kpis['totalMembers']) ? number_format($kpis['totalMembers']) : '—' }}
            </h2>
            <span class="cw-kpi-description">Registered members</span>
        </div>

        <div class="cw-kpi-card">
            <div class="cw-kpi-top">
                <span class="cw-kpi-icon teal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 9h.01M15 9h.01" />
                    </svg>
                </span>
                <span class="cw-kpi-label">ACTIVE WORKSPACES</span>
            </div>
            <h2>
                {{ isset($kpis['activeWorkspaces']) ? number_format($kpis['activeWorkspaces']) : '—' }}
            </h2>
            <span class="cw-kpi-description">Approved and bookable spaces</span>
        </div>

    </section>

    {{-- CHARTS SECTION --}}
    <section class="cw-charts-grid">

        <div class="cw-panel cw-revenue-panel">
            <div class="cw-panel-header">
                <div>
                    <h3>Revenue Performance</h3>
                    <p>Booking value and platform commission over time</p>
                </div>
                <span class="cw-panel-tag">Financial Overview</span>
            </div>

            <div class="cw-chart-container">
                <canvas id="revenueChart"></canvas>
                <div class="cw-chart-empty" id="revenueEmpty">
                    <span class="cw-empty-icon">⌁</span>
                    <strong>No revenue data available</strong>
                    <p>Revenue statistics will appear here once data is connected.</p>
                </div>
            </div>
        </div>

        <div class="cw-panel cw-bookings-panel">
            <div class="cw-panel-header">
                <div>
                    <h3>Booking Overview</h3>
                    <p>Booking status distribution</p>
                </div>
                <span class="cw-panel-tag">Bookings</span>
            </div>

            <div class="cw-donut-wrapper">
                <canvas id="bookingChart"></canvas>
                <div class="cw-chart-empty" id="bookingEmpty">
                    <span class="cw-empty-icon">◷</span>
                    <strong>No booking data available</strong>
                    <p>Booking statistics will appear here.</p>
                </div>
            </div>

            <div class="cw-chart-legend" id="bookingLegend"></div>
        </div>

    </section>

    {{-- BOTTOM SECTION --}}
    <section class="cw-bottom-grid">

        <div class="cw-panel cw-workspaces-panel">

            <div class="cw-panel-header">
                <div>
                    <h3>Top Performing Workspaces</h3>
                    <p>Workspaces ranked by booking activity</p>
                </div>
                <a href="#" class="cw-view-all">View All →</a>
            </div>

            <div class="cw-table-wrapper">
                <table class="cw-table">
                    <thead>
                        <tr>
                            <th>WORKSPACE</th>
                            <th>BOOKINGS</th>
                            <th>GMV</th>
                            <th>PERFORMANCE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="cw-empty-table">
                                <div class="cw-table-empty">
                                    <span>⌕</span>
                                    <strong>No workspace data available</strong>
                                    <p>Workspace performance will appear here.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        <div class="cw-panel cw-activity-panel">

            <div class="cw-panel-header">
                <div>
                    <h3>Recent Activity</h3>
                    <p>Latest platform activities</p>
                </div>
                <span class="cw-live-indicator">ACTIVITY</span>
            </div>

            <div class="cw-activity-list">
                <div class="cw-activity-empty">
                    <span>◷</span>
                    <strong>No recent activity</strong>
                    <p>Platform activity will appear here.</p>
                </div>
            </div>

        </div>

    </section>

    {{-- ALERTS --}}
    <section class="cw-panel cw-alerts-panel">
        <div class="cw-panel-header">
            <div>
                <h3>Platform Attention</h3>
                <p>Important issues that may require administrative action</p>
            </div>
            <span class="cw-alert-badge">MONITORING</span>
        </div>

        <div class="cw-alert-empty">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                <path d="m12 3 10 18H2L12 3Z" />
                <path d="M12 9v5m0 3h.01" />
            </svg>
            <div>
                <strong>No alerts to display</strong>
                <p>System alerts will appear here when monitoring is connected.</p>
            </div>
        </div>
    </section>

</main>

<style>
    .cw-dashboard {
        --cw-primary: #526044;
        --cw-primary-dark: #3d4933;
        --cw-bg: #f7f8f6;
        --cw-card: #ffffff;
        --cw-border: #e9ece7;
        --cw-text: #20271f;
        --cw-muted: #879087;

        min-height: 100vh;
        padding: 32px;
        background: var(--cw-bg);
        color: var(--cw-text);
        font-family: inherit;
        box-sizing: border-box;
    }

    .cw-dashboard *,
    .cw-dashboard *::before,
    .cw-dashboard *::after {
        box-sizing: border-box;
    }

    .cw-page-header,
    .cw-header-actions,
    .cw-kpi-top,
    .cw-panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .cw-page-header {
        margin-bottom: 30px;
        gap: 20px;
        flex-wrap: wrap;
    }

    .cw-eyebrow {
        color: var(--cw-primary);
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 2px;
    }

    .cw-page-header h1 {
        font-size: 29px;
        font-weight: 750;
        letter-spacing: -0.8px;
        margin: 7px 0;
    }

    .cw-page-header p,
    .cw-panel-header p {
        color: var(--cw-muted);
        font-size: 13px;
        margin: 0;
    }

    .cw-header-actions {
        gap: 10px;
        flex-wrap: wrap;
    }

    .cw-date-filter,
    .cw-export-btn {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 11px 14px;
        border: 1px solid var(--cw-border);
        border-radius: 9px;
        font-size: 12px;
        font-weight: 600;
        background: white;
        color: #4b5549;
    }

    .cw-date-filter svg,
    .cw-export-btn svg {
        width: 17px;
        height: 17px;
    }

    .cw-export-btn {
        background: var(--cw-primary);
        color: white;
        border-color: var(--cw-primary);
        cursor: not-allowed;
        opacity: .65;
    }

    /* KPI CARDS */

    .cw-kpi-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 15px;
        margin-bottom: 22px;
    }

    .cw-kpi-card {
        padding: 19px;
        background: var(--cw-card);
        border: 1px solid var(--cw-border);
        border-radius: 13px;
        min-width: 0;
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .cw-kpi-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(35, 48, 30, .06);
    }

    .cw-kpi-top {
        justify-content: flex-start;
        gap: 10px;
        margin-bottom: 18px;
    }

    .cw-kpi-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        flex-shrink: 0;
    }

    .cw-kpi-icon svg {
        width: 19px;
        height: 19px;
    }

    .cw-kpi-icon.green {
        background: #edf4e9;
        color: #607e4b;
    }

    .cw-kpi-icon.blue {
        background: #eaf1f8;
        color: #537caa;
    }

    .cw-kpi-icon.purple {
        background: #f0edfa;
        color: #8170b1;
    }

    .cw-kpi-icon.orange {
        background: #fbf0e5;
        color: #c1844b;
    }

    .cw-kpi-icon.teal {
        background: #e6f3f0;
        color: #4d8d7e;
    }

    .cw-kpi-label {
        color: #879087;
        font-size: 9px;
        font-weight: 800;
        letter-spacing: .65px;
        line-height: 1.5;
    }

    .cw-kpi-card h2 {
        font-size: clamp(20px, 2vw, 26px);
        font-weight: 750;
        letter-spacing: -1px;
        margin: 0 0 8px;
        overflow-wrap: anywhere;
    }

    .cw-kpi-card h2 small {
        font-size: 12px;
        color: var(--cw-muted);
        font-weight: 500;
        letter-spacing: 0;
    }

    .cw-kpi-description {
        color: var(--cw-muted);
        font-size: 10px;
        line-height: 1.5;
    }

    /* PANELS */

    .cw-charts-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.7fr) minmax(270px, 1fr);
        gap: 17px;
        margin-bottom: 20px;
    }

    .cw-panel {
        min-width: 0;
        padding: 22px;
        background: var(--cw-card);
        border: 1px solid var(--cw-border);
        border-radius: 13px;
    }

    .cw-panel-header {
        gap: 12px;
        margin-bottom: 20px;
    }

    .cw-panel-header h3 {
        margin: 0 0 6px;
        font-size: 15px;
        font-weight: 700;
        color: var(--cw-text);
    }

    .cw-panel-header p {
        font-size: 11px;
    }

    .cw-panel-tag {
        color: #718064;
        background: #f0f4ed;
        border-radius: 6px;
        padding: 7px 9px;
        font-size: 9px;
        font-weight: 700;
        white-space: nowrap;
    }

    .cw-chart-container {
        height: 270px;
        position: relative;
    }

    .cw-donut-wrapper {
        height: 220px;
        max-width: 260px;
        margin: 0 auto;
        position: relative;
    }

    .cw-chart-empty {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
        color: var(--cw-muted);
        background: #fff;
        z-index: 1;
    }

    .cw-empty-icon {
        font-size: 27px;
        color: #a7b09e;
        margin-bottom: 9px;
    }

    .cw-chart-empty strong,
    .cw-table-empty strong,
    .cw-activity-empty strong {
        font-size: 12px;
        color: #657060;
        font-weight: 650;
    }

    .cw-chart-empty p,
    .cw-table-empty p,
    .cw-activity-empty p {
        font-size: 10px;
        margin: 6px 0 0;
        line-height: 1.6;
    }

    .cw-chart-legend {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 14px;
        font-size: 10px;
        color: var(--cw-muted);
    }

    /* BOTTOM */

    .cw-bottom-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.55fr) minmax(270px, 1fr);
        gap: 17px;
        margin-bottom: 20px;
    }

    .cw-view-all {
        color: var(--cw-primary);
        font-size: 11px;
        font-weight: 650;
        text-decoration: none;
        white-space: nowrap;
    }

    .cw-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .cw-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .cw-table th {
        padding: 13px 10px;
        border-bottom: 1px solid var(--cw-border);
        color: #939b91;
        font-size: 9px;
        font-weight: 750;
        letter-spacing: .5px;
        white-space: nowrap;
    }

    .cw-table td {
        padding: 12px 10px;
        border-bottom: 1px solid #f1f2ef;
        font-size: 11px;
    }

    .cw-empty-table {
        height: 180px;
        text-align: center;
    }

    .cw-table-empty,
    .cw-activity-empty {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100%;
        color: var(--cw-muted);
    }

    .cw-table-empty span,
    .cw-activity-empty span {
        font-size: 25px;
        color: #a9b2a2;
        margin-bottom: 10px;
    }

    .cw-live-indicator,
    .cw-alert-badge {
        font-size: 9px;
        letter-spacing: .5px;
        font-weight: 750;
        color: #77876b;
        background: #f0f4ed;
        padding: 7px 10px;
        border-radius: 6px;
    }

    .cw-activity-list {
        min-height: 180px;
    }

    .cw-alerts-panel {
        margin-bottom: 20px;
    }

    .cw-alert-empty {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 16px;
        background: #f8f9f7;
        border-radius: 9px;
        color: var(--cw-muted);
    }

    .cw-alert-empty svg {
        width: 24px;
        height: 24px;
        flex-shrink: 0;
        color: #8b967e;
    }

    .cw-alert-empty strong {
        display: block;
        color: #596451;
        font-size: 12px;
    }

    .cw-alert-empty p {
        margin: 5px 0 0;
        font-size: 10px;
    }

    /* RESPONSIVE */

    @media (max-width: 1250px) {
        .cw-kpi-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {

        .cw-charts-grid,
        .cw-bottom-grid {
            grid-template-columns: 1fr;
        }

        .cw-dashboard {
            padding: 22px;
        }
    }

    @media (max-width: 600px) {
        .cw-dashboard {
            padding: 15px;
        }

        .cw-kpi-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .cw-kpi-card {
            padding: 14px;
        }

        .cw-kpi-top {
            flex-direction: column;
            align-items: flex-start;
            gap: 9px;
        }

        .cw-kpi-label {
            font-size: 8px;
        }

        .cw-page-header h1 {
            font-size: 24px;
        }

        .cw-header-actions {
            width: 100%;
        }

        .cw-date-filter,
        .cw-export-btn {
            justify-content: center;
            flex: 1;
        }

        .cw-panel {
            padding: 15px;
        }

        .cw-panel-header {
            align-items: flex-start;
        }
    }
</style>
