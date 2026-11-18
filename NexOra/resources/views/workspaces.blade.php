@php
    $isPreview = !isset($workspaces) && !isset($workspace);
    $spaces = collect($workspaces ?? $workspace ?? [
        ['name' => 'The Foundry', 'location' => 'Abdali, Amman', 'manager' => ['full_name' => 'Lina Haddad'], 'approval_status' => 'approved', 'is_active' => true],
        ['name' => 'Studio North', 'location' => 'Sweifieh, Amman', 'manager' => ['full_name' => 'Omar Saleh'], 'approval_status' => 'approved', 'is_active' => true],
        ['name' => 'The Olive Loft', 'location' => 'Jabal Al Weibdeh, Amman', 'manager' => ['full_name' => 'Maya Nasser'], 'approval_status' => 'pending', 'is_active' => true],
        ['name' => 'Common Ground', 'location' => 'Shmeisani, Amman', 'manager' => ['full_name' => 'Yousef Khalil'], 'approval_status' => 'approved', 'is_active' => true],
        ['name' => 'Corner Office', 'location' => 'Um Uthaina, Amman', 'manager' => ['full_name' => 'Noor Samir'], 'approval_status' => 'approved', 'is_active' => false],
        ['name' => 'Atlas House', 'location' => 'Jabal Amman, Amman', 'manager' => ['full_name' => 'Dana Zaid'], 'approval_status' => 'pending', 'is_active' => true],
    ])->map(function ($space) {
        $approval = (string) data_get($space, 'approval_status', 'pending');
        $active = (bool) data_get($space, 'is_active', false);
        $status = !$active ? 'inactive' : ($approval === 'approved' ? 'active' : $approval);

        return [
            'name' => data_get($space, 'name') ?: 'Unnamed workspace',
            'location' => data_get($space, 'location') ?: '—',
            'manager' => data_get($space, 'manager.full_name') ?: 'Unassigned',
            'status' => $status,
        ];
    });
    $total = $spaces->count();
    $counts = $spaces->countBy('status');
    $statuses = [
        'active' => ['label' => 'Active', 'color' => '#6046e6'],
        'pending' => ['label' => 'Pending', 'color' => '#f3ae4b'],
        'inactive' => ['label' => 'Inactive', 'color' => '#a7a5b8'],
        'rejected' => ['label' => 'Rejected', 'color' => '#ce5274'],
    ];
    foreach ($counts->keys() as $key) {
        $statuses[$key] ??= ['label' => \Illuminate\Support\Str::headline($key), 'color' => '#64748b'];
    }
    $activePercent = $total ? round(100 * $counts->get('active', 0) / $total) : 0;
    $chartStops = [];
    $chartCount = 0;
    if ($total) {
        foreach ($statuses as $key => $status) {
            $count = $counts->get($key, 0);
            if ($count > 0) {
                $start = 100 * $chartCount / $total;
                $chartCount += $count;
                $end = 100 * $chartCount / $total;
                $chartStops[] = "{$status['color']} {$start}% {$end}%";
            }
        }
    }
    $chartBackground = $chartStops ? 'conic-gradient(' . implode(', ', $chartStops) . ')' : '#efedf5';
    $metrics = [
        ['label' => 'Total workspaces', 'value' => $total, 'detail' => 'Across all locations', 'icon' => 'spaces'],
        ['label' => 'Active', 'value' => $counts->get('active', 0), 'detail' => 'Approved and operational', 'icon' => 'active'],
        ['label' => 'Pending review', 'value' => $counts->get('pending', 0), 'detail' => 'Awaiting approval', 'icon' => 'pending'],
        ['label' => 'Inactive', 'value' => $counts->get('inactive', 0), 'detail' => 'Deactivated workspaces', 'icon' => 'inactive'],
    ];
    $icons = [
        'spaces' => 'M3 21h18M5 21V5h9v16m0-11h5v11M8 8h3m-3 4h3m-3 4h3',
        'active' => 'M20 11a8 8 0 1 1-4.1-6.9M8.5 11.5l2.8 2.8L20 5.5',
        'pending' => 'M12 8v4l2.8 1.7M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        'inactive' => 'M8 9h8v6H8zM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
        'search' => 'm20 20-4.2-4.2m2.2-4.3a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z',
        'arrow' => 'M12 5v14m-5-5 5 5 5-5',
    ];
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Workspaces | NexOra Admin</title>
    <style>
        :root {
            color-scheme: light;
            --ink: #1e1933;
            --muted: #615c73;
            --line: #ebe8f1;
            --accent: #6046e6;
            --surface: #fff;
            --canvas: #f6f5fa;
            font-family: "Segoe UI", system-ui, sans-serif;
            scroll-padding-top: 20px;
        }
        *, *::before, *::after { box-sizing: border-box; }
        body { margin: 0; color: var(--ink); background: var(--canvas); }
        a { color: inherit; text-decoration: none; }
        input, select { font: inherit; }
        :focus-visible { outline: 3px solid #9d84ff; outline-offset: 3px; }
        [hidden] { display: none !important; }
        .icon { width: 20px; height: 20px; flex: none; fill: none; stroke: currentColor; stroke-width: 1.65; stroke-linecap: round; stroke-linejoin: round; }
        .topbar { background: #fff; border-bottom: 1px solid var(--line); }
        .topbar-inner, main { width: min(1240px, calc(100% - 72px)); margin-inline: auto; }
        .topbar-inner { min-height: 70px; display: flex; align-items: center; gap: 38px; }
        .brand { display: inline-flex; align-items: center; gap: 10px; font-size: 21px; font-weight: 780; letter-spacing: -.055em; white-space: nowrap; }
        .brand-mark { width: 34px; height: 34px; display: grid; place-items: center; border-radius: 11px; color: #fff; background: var(--accent); }
        .nav { align-self: stretch; display: flex; }
        .nav a { display: grid; align-items: center; padding-inline: 4px; color: #5238ce; font-size: 14px; font-weight: 700; border-bottom: 2px solid var(--accent); }
        .admin { margin-inline-start: auto; display: inline-flex; align-items: center; gap: 9px; font-size: 13px; font-weight: 650; }
        .admin-mark { display: grid; place-items: center; width: 32px; height: 32px; border-radius: 50%; background: #eee9ff; color: #5238ce; }
        main { padding-block: 38px 48px; }
        .hero { position: relative; isolation: isolate; overflow: hidden; display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; min-height: 240px; padding: 36px 40px; border-radius: 24px; color: #fff; background: linear-gradient(112deg, #25174d, #442ba2 65%, #6245e6); }
        .hero::before, .hero::after { content: ""; position: absolute; z-index: -1; pointer-events: none; }
        .hero::before { inset: 0 0 0 42%; opacity: .28; background-image: linear-gradient(#bcaaff 1px, transparent 1px), linear-gradient(90deg, #bcaaff 1px, transparent 1px); background-size: 30px 30px; mask-image: linear-gradient(90deg, transparent, #000); }
        .hero::after { width: 310px; height: 310px; right: 40px; bottom: -105px; border: 1px solid #cabaff70; border-radius: 68px; transform: rotate(32deg); box-shadow: 0 0 0 25px #cabaff1e, 0 0 0 50px #cabaff14, 0 0 0 77px #cabaff10; }
        .preview { display: inline-block; padding: 5px 11px; border-radius: 99px; background: #ffffff20; font-size: 12px; font-weight: 650; }
        h1, h2, p { margin: 0; }
        h1 { margin-block: 6px 7px; font-size: clamp(2.7rem, 5vw, 4.25rem); line-height: 1.04; letter-spacing: -.075em; }
        .hero p { color: #eee6ff; font-size: 15px; line-height: 1.55; }
        .hero-link { display: inline-flex; align-items: center; justify-content: center; gap: 9px; flex: none; min-height: 44px; padding-inline: 15px; border-radius: 10px; color: #352077; background: #fff; font-size: 13px; font-weight: 750; }
        .hero-link:hover { background: #eee6ff; }
        .hero-link .icon { width: 16px; height: 16px; }
        .metrics { margin-block: 22px; display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 16px; }
        .metric, .panel { background: var(--surface); border: 1px solid var(--line); border-radius: 18px; box-shadow: 0 4px 16px #21133f06; }
        .metric { padding: 17px 19px; }
        .metric-top { display: flex; align-items: center; gap: 11px; font-size: 13px; font-weight: 650; }
        .metric-icon { display: grid; place-items: center; width: 35px; height: 35px; flex: none; border-radius: 10px; background: #eee9ff; color: #563ccd; }
        .metric[data-metric="active"] .metric-icon { background: #e2f7ee; color: #167455; }
        .metric[data-metric="pending"] .metric-icon { background: #fff1d9; color: #895200; }
        .metric[data-metric="inactive"] .metric-icon { background: #efedf5; color: #615975; }
        .metric dd { margin: 7px 0 0; font-size: 34px; line-height: 1.1; font-weight: 750; letter-spacing: -.06em; font-variant-numeric: tabular-nums; }
        .metric p { margin-top: 5px; color: var(--muted); font-size: 12px; font-weight: 400; letter-spacing: normal; }
        .overview-grid { display: grid; grid-template-columns: minmax(0, 1fr) minmax(265px, .37fr); align-items: start; gap: 16px; }
        .panel { min-width: 0; }
        .panel-heading { padding: 21px 21px 16px; }
        h2 { font-size: 19px; line-height: 1.25; letter-spacing: -.035em; }
        .subtle { margin-top: 5px; color: var(--muted); font-size: 13px; }
        .filters { display: flex; gap: 9px; margin-top: 15px; }
        .search { position: relative; flex: 1; min-width: 0; }
        .search .icon { position: absolute; top: 12px; left: 12px; width: 16px; height: 16px; color: var(--muted); pointer-events: none; }
        .filters input, .filters select { width: 100%; min-height: 40px; border: 1px solid #ded9eb; border-radius: 9px; color: var(--ink); background: #fff; font-size: 13px; }
        .filters input { padding: 8px 10px 8px 36px; }
        .filters select { width: auto; max-width: 45%; padding: 8px; }
        .sr-only { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip-path: inset(50%); white-space: nowrap; border: 0; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 13px; text-align: left; }
        th, td { padding: 11px 15px; border-top: 1px solid var(--line); }
        th:first-child, td:first-child { padding-left: 21px; }
        th:last-child, td:last-child { padding-right: 21px; }
        thead th { color: #554d68; background: #faf9fd; font-size: 12px; font-weight: 650; }
        tbody th { font-weight: 680; }
        tbody td { color: var(--muted); }
        .workspace-name { display: flex; align-items: center; gap: 9px; min-width: 132px; }
        .initial { display: grid; place-items: center; width: 32px; height: 32px; flex: none; border-radius: 9px; background: #eee9ff; color: #5238c6; font-size: 12px; }
        tr:nth-child(3n + 2) .initial { background: #e4f6ee; color: #216852; }
        tr:nth-child(3n + 3) .initial { background: #ffeddc; color: #8b4625; }
        .pill { display: inline-flex; align-items: center; gap: 5px; padding: 4px 8px; border-radius: 99px; background: #efedf5; color: #574d6c; font-size: 11px; font-weight: 700; white-space: nowrap; }
        .pill::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
        .pill[data-status="active"] { color: #176347; background: #e2f7ed; }
        .pill[data-status="pending"] { color: #7b4b00; background: #fff0d4; }
        .pill[data-status="rejected"] { color: #a33052; background: #fcebf0; }
        .table-foot { padding: 12px 21px; border-top: 1px solid var(--line); color: var(--muted); font-size: 12px; }
        .empty { padding-block: 26px; text-align: center; }
        .chart-body { padding: 0 21px 18px; }
        .chart { width: 152px; height: 152px; margin: 5px auto 21px; display: grid; place-items: center; border-radius: 50%; background: var(--status-chart); }
        .chart-center { width: 123px; height: 123px; display: grid; align-content: center; justify-items: center; border-radius: 50%; background: var(--surface); }
        .chart-center strong { font-size: 31px; line-height: 1.05; letter-spacing: -.055em; }
        .chart-center span { color: var(--muted); font-size: 12px; }
        .legend { list-style: none; margin: 0; padding: 0; }
        .legend li { display: flex; align-items: center; gap: 8px; padding: 9px 0; border-top: 1px solid var(--line); font-size: 13px; }
        .dot { width: 8px; height: 8px; flex: none; border-radius: 50%; background: var(--dot-color); }
        .legend strong { margin-inline-start: auto; font-variant-numeric: tabular-nums; }
        @media (max-width: 1000px) {
            .overview-grid { grid-template-columns: minmax(0, 1fr); }
            .chart-body { display: grid; grid-template-columns: 180px 1fr; align-items: center; gap: 12px; }
        }
        @media (max-width: 720px) {
            .topbar-inner, main { width: calc(100% - 32px); }
            .topbar-inner { gap: 18px; min-height: 60px; }
            main { padding-top: 20px; }
            .hero { min-height: 220px; padding: 24px; }
            .metrics { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; }
            .metric { padding: 13px; }
        }
        @media (max-width: 480px) {
            .admin-label { display: none; }
            .hero { flex-direction: column; align-items: flex-start; justify-content: center; }
            .hero p { font-size: 14px; }
            .metric-top { gap: 6px; }
            .metric-icon { width: 28px; height: 28px; }
            .metric-icon .icon { width: 17px; height: 17px; }
            .chart-body { display: block; }
        }
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <span class="brand">
                <span class="brand-mark">
                    <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $icons['spaces'] }}"/></svg>
                </span>
                NexOra
            </span>
            <nav class="nav" aria-label="Admin navigation">
                <a href="#overview" aria-current="page">Workspaces</a>
            </nav>
            <span class="admin"><span class="admin-mark" aria-hidden="true">A</span><span class="admin-label">Admin</span></span>
        </div>
    </header>

    <main id="overview">
        <section class="hero" aria-labelledby="page-title">
            <div>
                @if ($isPreview)
                    <span class="preview">Sample data</span>
                @endif
                <h1 id="page-title">Workspaces</h1>
                <p>Track workspace activity, approvals and locations.</p>
            </div>
            <a class="hero-link" href="#directory">
                View directory
                <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $icons['arrow'] }}"/></svg>
            </a>
        </section>

        <dl class="metrics" aria-label="Workspace statistics">
            @foreach ($metrics as $metric)
                <div class="metric" data-metric="{{ $metric['icon'] }}">
                    <dt class="metric-top">
                        <span class="metric-icon">
                            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $icons[$metric['icon']] }}"/></svg>
                        </span>
                        {{ $metric['label'] }}
                    </dt>
                    <dd>{{ number_format($metric['value']) }}<p>{{ $metric['detail'] }}</p></dd>
                </div>
            @endforeach
        </dl>

        <div class="overview-grid">
            <section id="directory" class="panel" aria-labelledby="directory-title">
                <div class="panel-heading">
                    <h2 id="directory-title">Workspace directory</h2>
                    @if ($total)
                        <div id="workspace-filters" class="filters" hidden>
                            <label class="search">
                                <span class="sr-only">Search workspaces</span>
                                <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $icons['search'] }}"/></svg>
                                <input id="workspace-search" type="search" placeholder="Search name, location or manager">
                            </label>
                            <label class="sr-only" for="workspace-status">Filter by status</label>
                            <select id="workspace-status">
                                <option value="">All statuses</option>
                                @foreach ($statuses as $key => $status)
                                    @if ($counts->has($key))
                                        <option value="{{ $key }}">{{ $status['label'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>
                <div class="table-wrap" role="region" aria-label="Workspaces table" tabindex="0">
                    <table>
                        <thead>
                            <tr>
                                <th scope="col">Workspace</th>
                                <th scope="col">Location</th>
                                <th scope="col">Manager</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($spaces as $space)
                                <tr data-workspace-status="{{ $space['status'] }}">
                                    <th scope="row">
                                        <span class="workspace-name">
                                            <span class="initial" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($space['name'], 0, 1)) }}</span>
                                            <span data-searchable>{{ $space['name'] }}</span>
                                        </span>
                                    </th>
                                    <td data-searchable>{{ $space['location'] }}</td>
                                    <td data-searchable>{{ $space['manager'] }}</td>
                                    <td><span class="pill" data-status="{{ $space['status'] }}">{{ $statuses[$space['status']]['label'] }}</span></td>
                                </tr>
                            @empty
                                <tr><td class="empty" colspan="4">No workspaces have been added yet.</td></tr>
                            @endforelse
                            <tr id="workspace-no-results" hidden><td class="empty" colspan="4">No workspaces match these filters.</td></tr>
                        </tbody>
                    </table>
                </div>
                <p id="workspace-result-count" class="table-foot" role="status">{{ number_format($total) }} of {{ number_format($total) }} workspaces</p>
            </section>

            <section class="panel" aria-labelledby="status-title">
                <div class="panel-heading">
                    <h2 id="status-title">Workspace status</h2>
                    <p class="subtle">Activity across all workspaces</p>
                </div>
                <div class="chart-body">
                    <div class="chart" style="--status-chart: {{ $chartBackground }}">
                        <div class="chart-center"><strong>{{ $total ? $activePercent . '%' : '—' }}</strong><span>{{ $total ? 'Active' : 'No data' }}</span></div>
                    </div>
                    <ul class="legend" aria-label="Status totals">
                        @foreach ($statuses as $key => $status)
                            @if ($counts->has($key) || in_array($key, ['active', 'pending', 'inactive'], true))
                                <li><span class="dot" style="--dot-color: {{ $status['color'] }}" aria-hidden="true"></span>{{ $status['label'] }} <strong>{{ number_format($counts->get($key, 0)) }}</strong></li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </section>
        </div>
    </main>

    @if ($total)
        <script>
            const search = document.querySelector('#workspace-search');
            const statusFilter = document.querySelector('#workspace-status');
            const rows = Array.from(document.querySelectorAll('[data-workspace-status]'), row => ({
                row,
                status: row.dataset.workspaceStatus,
                text: Array.from(row.querySelectorAll('[data-searchable]'), cell => cell.textContent.trim()).join(' ').toLocaleLowerCase(),
            }));
            const resultCount = document.querySelector('#workspace-result-count');
            const noResults = document.querySelector('#workspace-no-results');
            const formatCount = new Intl.NumberFormat(document.documentElement.lang);

            function filterWorkspaces() {
                const query = search.value.trim().toLocaleLowerCase();
                const status = statusFilter.value;
                let visible = 0;

                rows.forEach(({ row, status: rowStatus, text }) => {
                    const matches = (!status || status === rowStatus) && text.includes(query);
                    row.hidden = !matches;
                    visible += Number(matches);
                });

                noResults.hidden = visible > 0;
                resultCount.textContent = `${formatCount.format(visible)} of ${formatCount.format(rows.length)} workspaces`;
            }

            search.addEventListener('input', filterWorkspaces);
            statusFilter.addEventListener('change', filterWorkspaces);
            filterWorkspaces();
            document.querySelector('#workspace-filters').hidden = false;
        </script>
    @endif
</body>
</html>
