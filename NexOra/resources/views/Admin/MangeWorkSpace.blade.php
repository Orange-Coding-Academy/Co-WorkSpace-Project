@php
$workspaceId = request()->query('workspace');
$selectedWorkspace = is_string($workspaceId)
? $workspaces->first(fn ($workspace) => (string) $workspace->getKey() === $workspaceId)
: null;
@endphp

@if ($selectedWorkspace)
@include('Admin.WorkspaceDetails', ['workspace' => $selectedWorkspace])
@else
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manage workspaces</title>
    <style>
        :root {
            color-scheme: light;
            --ink: #211a35;
            --muted: #5d5670;
            --line: #e8e3ef;
            --purple: #6042df;
            --amber: #f3aa46;
            --grey: #a6a2b5;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f5f3f9;
            color: var(--ink);
            font: 1rem/1.5 system-ui, sans-serif;
        }

        main {
            max-width: 1800px;
            margin: auto;
            padding: clamp(12px, 1.6vw, 24px);
        }

        .summary,
        .dashboard {
            display: grid;
            gap: 16px;
        }

        .summary {
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin-bottom: 16px;
        }

        .dashboard {
            grid-template-columns: minmax(0, 1fr);
            align-items: start;
        }

        .card {
            background: #fff;
            border: 1px solid var(--line);
            border-radius: 24px;
            box-shadow: 0 8px 24px #211a3505;
        }

        .summary .card {
            padding: 14px 16px;
        }

        .metric-title,
        .workspace {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .icon,
        .initial {
            display: inline-grid;
            flex: none;
            place-items: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #ede7ff;
            color: #5033c4;
        }

        .icon svg {
            width: 23px;
            height: 23px;
        }

        .metric-number {
            display: block;
            margin-top: 2px;
            font-size: 1.9rem;
            line-height: 1.15;
        }

        .muted {
            color: var(--muted);
        }

        h2,
        p {
            margin: 0;
        }

        h2 {
            font-size: 1.5rem;
            letter-spacing: -.025em;
        }

        .directory-header,
        .status-panel {
            padding: 16px 20px;
        }

        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 12px;
        }

        .filters input,
        .filters select {
            border: 1px solid #ded6ee;
            border-radius: 12px;
            background: #fff;
            color: inherit;
            font: inherit;
            padding: 10px 12px;
        }

        .filters input {
            flex: 1 1 230px;
            min-width: 0;
        }

        .table-scroll {
            overflow-x: auto;
            border-radius: 0 0 24px 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px 16px;
            text-align: left;
            border-top: 1px solid var(--line);
        }

        th {
            background: #f8f6fb;
            color: #4b435f;
            font-size: .9rem;
        }

        th:first-child,
        td:first-child {
            padding-left: 20px;
        }

        td {
            color: var(--muted);
        }

        .workspace {
            color: var(--ink);
            font-weight: 700;
        }

        .initial {
            width: 36px;
            height: 36px;
        }

        tr:nth-child(3n + 2) .initial {
            background: #e0f5ec;
            color: #16634d;
        }

        tr:nth-child(3n) .initial {
            background: #ffecd9;
            color: #8a421b;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 3px 9px;
            font-size: .85rem;
            font-weight: 700;
        }

        .active {
            background: #def5eb;
            color: #16634d;
        }

        .pending {
            background: #fff0d5;
            color: #814c00;
        }

        .inactive {
            background: #eeebf4;
            color: #554b69;
        }

        .badge::before,
        .legend-label::before {
            content: "";
            width: 8px;
            height: 8px;
            flex: none;
            border-radius: 50%;
            background: currentColor;
        }

        .status-panel>p {
            margin-top: 4px;
        }

        .donut {
            position: relative;
            display: grid;
            place-items: center;
            width: min(100%, 190px);
            aspect-ratio: 1;
            margin: 26px auto;
            border-radius: 50%;
            background: conic-gradient(var(--purple) 0 var(--active-share),
                    var(--amber) var(--active-share) var(--review-share),
                    var(--grey) var(--review-share) 100%);
        }

        .donut::before {
            content: "";
            position: absolute;
            inset: 9%;
            background: white;
            border-radius: 50%;
        }

        .donut-label {
            position: relative;
            text-align: center;
        }

        .donut-label strong {
            display: block;
            font-size: 2.3rem;
            line-height: 1.15;
        }

        .legend {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .legend li {
            display: flex;
            justify-content: space-between;
            border-top: 1px solid var(--line);
            padding: 10px 0;
        }

        .legend-label {
            display: inline-flex;
            align-items: center;
            gap: 9px;
        }

        .legend-active::before {
            background: var(--purple);
        }

        .legend-pending::before {
            background: var(--amber);
        }

        .legend-inactive::before {
            background: var(--grey);
        }

        .empty {
            padding: 26px;
            text-align: center;
        }

        @media (max-width: 980px) {
            .summary {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .dashboard {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        @media (max-width: 480px) {
            .summary {
                gap: 12px;
            }

            .summary .card,
            .directory-header,
            .status-panel {
                padding: 16px;
            }

            .metric-title {
                gap: 8px;
            }

            .icon {
                width: 32px;
                height: 32px;
            }
        }

        .workspace-actions {
            text-align: right;
            white-space: nowrap;
        }

        .badge {
            white-space: nowrap;
        }

        .workspace-action {
            min-width: 92px;
            padding: 6px 12px;
            border: 1px solid var(--action-border);
            border-radius: 9px;
            background: var(--action-background);
            color: var(--action-ink);
            font: inherit;
            font-size: .85rem;
            font-weight: 700;
            cursor: pointer;
        }

        .workspace-action--disable {
            --action-background: #fff1f2;
            --action-border: #f3c6cd;
            --action-ink: #9f2336;
            --action-hover: #ffe1e6;
        }

        .workspace-action--review {
            --action-background: #fff6df;
            --action-border: #efd49a;
            --action-ink: #805000;
            --action-hover: #ffebbd;
        }

        .workspace-action--enable {
            --action-background: #e7f7ef;
            --action-border: #b7dfcd;
            --action-ink: #176248;
            --action-hover: #d2f0e2;
        }

        .workspace-action:hover:not(:disabled) {
            border-color: var(--action-ink);
            background: var(--action-hover);
        }

        .workspace-action:focus-visible {
            outline: 2px solid var(--purple);
            outline-offset: 2px;
        }

        .workspace-action:disabled {
            border-color: var(--line);
            background: #f5f3f8;
            color: var(--muted);
            cursor: default;
        }

        .metric-title>div {
            min-width: 0;
        }

        .summary .card,
        .workspace-action,
        .filters input,
        .filters select {
            transition: background-color .15s ease, border-color .15s ease, box-shadow .15s ease;
        }

        th.rooms-column,
        td.rooms-column {
            text-align: center;
        }

        .rooms-column {
            width: 1%;
            font-variant-numeric: tabular-nums;
        }

        table {
            min-width: 720px;
        }

        .filters :focus-visible,
        .table-scroll:focus-visible {
            outline: 2px solid var(--purple);
            outline-offset: 2px;
        }

        tbody tr[data-workspace-status]:focus-within {
            background: #faf7ff;
        }

        @media (hover: hover) {
            .summary .card:hover {
                border-color: #d9ccef;
                box-shadow: 0 6px 18px #44258212;
            }

            tbody tr[data-workspace-status]:hover {
                background: #faf7ff;
            }

            .filters input:hover,
            .filters select:hover {
                border-color: #b9a8e5;
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .summary .card,
            .workspace-action,
            .filters input,
            .filters select {
                transition: none;
            }
        }

        .workspace-details-link {
            border: 0;
            padding: 0;
            background: transparent;
            font: inherit;
            font-weight: 700;
            text-align: left;
            text-decoration: none;
        }

        .workspace-details-label {
            display: block;
            margin-top: 2px;
            color: var(--purple);
            font-size: .75rem;
            font-weight: 600;
        }

        .workspace-details-link:hover .workspace-details-label,
        .workspace-details-link:focus-visible .workspace-details-label {
            text-decoration: underline;
            text-underline-offset: 3px;
        }
    </style>
</head>

<body>
    <main aria-label="Manage workspaces">


        <section class="summary" aria-label="Workspace summary">
            @foreach ($statistics as $key => $count)
            <article class="card metric-title">
                <span class="icon {{ $key }}" aria-hidden="true">

                    <span class="icon {{ $key }}" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.75" stroke-linecap="round"
                            stroke-linejoin="round" focusable="false">

                            @if ($key === 'active')
                            {{-- علامة صح --}}
                            <circle cx="12" cy="12" r="9" />
                            <path d="m8 12 3 3 5-6" />
                            @elseif ($key === 'pending')
                            {{-- ساعة --}}
                            <circle cx="12" cy="12" r="9" />
                            <path d="M12 7v5l3 2" />
                            @elseif ($key === 'inactive')
                            {{-- علامة ناقص --}}
                            <circle cx="12" cy="12" r="9" />
                            <path d="M8 12h8" />
                            @else
                            {{-- مبنى لبطاقة Total --}}
                            <rect x="6" y="3" width="12" height="18" rx="2" />
                            <path d="M10 7h4m-4 4h4m-4 4h4" />
                            @endif

                        </svg>
                    </span>
                </span>
                <div>
                    <strong>{{ ucfirst($key) }}</strong>
                    <strong class="metric-number">{{ number_format($count) }}</strong>
                </div>
            </article>
            @endforeach
        </section>

        <div class="dashboard">
            <section class="card" aria-labelledby="directory-title">
                <div class="directory-header">
                    <h2 id="directory-title">Workspace directory</h2>
                    <div class="filters" role="search">
                        <input type="search" id="workspace-search"
                            placeholder="Search name, location or manager" aria-label="Search name, location or manager">
                        <select id="workspace-status" aria-label="Filter by status">
                            <option value="">All statuses</option>
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="table-scroll" tabindex="0" role="region" aria-label="Workspace results">
                    <table>
                        <thead>
                            <tr>
                                <th scope="col">Workspace</th>

                                <th scope="col">Location</th>
                                <th scope="col" class="rooms-column">Rooms</th>
                                <th scope="col">Manager</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="workspace-actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($workspaces as $workspace)

                            <tr data-workspace-status="">
                                <td>

                                    <a class="workspace workspace-details-link"
                                        href="{{ route('admin.workspace.details',$workspace->id ) }}">
                                        <span class="initial" aria-hidden="true">{{ mb_strtoupper(mb_substr($workspace->name, 0, 1)) }}</span>
                                        <span>
                                            <span>{{ $workspace->name }}</span>
                                            <span class="workspace-details-label">View details <span aria-hidden="true">&rarr;</span></span>
                                        </span>
                                    </a>
                                </td>
                                <td>{{ $workspace->location ?: 'Location unavailable' }}</td>
                                <td class="rooms-column">{{ $workspace->rooms->count() }}</td>
                                <td>{{ $workspace->manager?->full_name ?? $workspace->manager?->name ?? 'Unassigned' }}</td>
                                <td>
                                    <span @class([ 'badge' , 'active'=> $workspace->approval_status === 'approved',
                                        'pending' => $workspace->approval_status === 'pending',
                                        'inactive' => ! in_array($workspace->approval_status, ['approved', 'pending'], true),
                                        ])>
                                        {{ ucfirst($workspace->approval_status) }}
                                    </span>
                                </td>
                                <td class="workspace-actions">
                                    @if (! $workspace->is_active)
                                    <form method="POST" action="{{ route('admin.Enable', $workspace) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="workspace-action workspace-action--enable">
                                            Enable
                                        </button>
                                    </form>
                                    @elseif ($workspace->approval_status === 'pending')
                                    <form method="POST" action="{{ route('admin.UpdateStatus', $workspace) }}">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="workspace-action workspace-action--review">
                                            Review
                                        </button>
                                    </form>
                                    @elseif ($workspace->approval_status === 'approved')
                                    <form method="POST"
                                        action="{{Route('admin.destroy',$workspace)}}"
                                        onsubmit="return confirm('Disable this workspace?')">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit" class="workspace-action workspace-action--disable">Disable</button>
                                    </form>
                                    @else
                                    <span aria-label="No available actions">&mdash;</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            <tr id="workspace-empty" @if ($workspaces->isNotEmpty()) hidden @endif>
                                <td class="empty" colspan="6" role="status">
                                    {{ $workspaces->isEmpty() ? 'No workspaces yet.' : 'No workspaces match your filters.' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>


        </div>
    </main>
    <script>
        const searchInput = document.querySelector('#workspace-search');
        const statusFilter = document.querySelector('#workspace-status');
        const emptyRow = document.querySelector('#workspace-empty');
        const workspaceRows = document.querySelectorAll('[data-workspace-status]');

        function filterWorkspaces() {
            const search = searchInput.value.trim().toLocaleLowerCase();
            const status = statusFilter.value;
            let hasResults = false;

            for (const row of workspaceRows) {
                const workspaceSearchFields = [row.querySelector('.workspace-details-link span span').textContent, row.cells[1].textContent, row.cells[3].textContent];
                const matchesSearch = workspaceSearchFields.some(value =>
                    value.toLocaleLowerCase().includes(search)
                );
                const matchesStatus = !status || row.dataset.workspaceStatus === status;
                row.hidden = !(matchesSearch && matchesStatus);
                hasResults ||= !row.hidden;
            }

            emptyRow.hidden = hasResults;
        }

        searchInput.addEventListener('input', filterWorkspaces);
        statusFilter.addEventListener('change', filterWorkspaces);
        filterWorkspaces();
    </script>
</body>

</html>
@endif