@extends('layouts.dashboard')

@section('dashboard-role', 'admin')

@php
$workspaceId = request()->query('workspace');
$selectedWorkspace = is_string($workspaceId)
? $workspaces->first(fn ($workspace) => (string) $workspace->getKey() === $workspaceId)
: null;
@endphp

@if ($selectedWorkspace)
@include('Admin.partials.workspace-details', ['workspace' => $selectedWorkspace])
@else
@section('title') Manage workspaces @endsection
@section('page-title') Manage workspaces @endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-shared-03.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-shared-04.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nexora/workspace-pages.css') }}?v=20261125-ui5">
@endpush

@section('content')
<div class="nx-page-content nx-workspace-ui nx-workspace-directory">
    <div data-nx-page-main aria-label="Manage workspaces">


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
                    <div class="directory-title-row">
                        <div>
                            <p class="directory-eyebrow">Administration</p>
                            <h2 id="directory-title">Workspace directory</h2>
                            <p class="directory-copy">Review workspace activity, approval status, and room availability.</p>
                        </div>
                        <span class="directory-count">Total workspaces: {{ $workspaces->count() }}</span>
                    </div>
                    <div class="filters" role="search">
                        <div class="filter-field">
                            <label for="workspace-search">Search workspaces</label>
                            <input type="search" id="workspace-search"
                                placeholder="Search name, location or manager">
                        </div>

                        <div class="filter-field">
                            <label for="workspace-status">Status</label>
                            <select id="workspace-status">
                                <option value="">All statuses</option>
                                <option value="active">Active</option>
                                <option value="pending">Pending</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div class="filter-actions">
                            <button type="button" class="filter-button filter-button--reset" id="reset-workspace-filters">Reset</button>
                        </div>
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

                            <tr data-workspace-status="{{ ! $workspace->is_active ? 'inactive' : ($workspace->approval_status === 'pending' ? 'pending' : 'active') }}">
                                <td data-label="Workspace">

                                    <a class="workspace workspace-details-link"
                                        href="{{ route('admin.workspace.details',$workspace->id ) }}">
                                        <span class="initial" aria-hidden="true">{{ mb_strtoupper(mb_substr($workspace->name, 0, 1)) }}</span>
                                        <span>
                                            <span>{{ $workspace->name }}</span>
                                            <span class="workspace-details-label">View details <span aria-hidden="true">&rarr;</span></span>
                                        </span>
                                    </a>
                                </td>
                                <td data-label="Location">{{ $workspace->location ?: 'Location unavailable' }}</td>
                                <td class="rooms-column" data-label="Rooms">{{ $workspace->rooms->count() }}</td>
                                <td data-label="Manager">{{ $workspace->manager?->full_name ?? $workspace->manager?->name ?? 'Unassigned' }}</td>
                                <td data-label="Status">
                                    <span @class([ 'badge' , 'active'=> $workspace->approval_status === 'approved',
                                        'pending' => $workspace->approval_status === 'pending',
                                        'inactive' => ! in_array($workspace->approval_status, ['approved', 'pending'], true),
                                        ])>
                                        {{ ucfirst($workspace->approval_status) }}
                                    </span>
                                </td>
                                <td class="workspace-actions" data-label="Actions">
                                    <div class="workspace-actions-group">
                                        <a href="{{ route('admin.workspace.details', $workspace->id) }}"
                                           class="workspace-action workspace-action--view">
                                            View
                                        </a>

                                        @if (! $workspace->is_active)
                                        <form method="POST" action="{{ route('admin.Enable', $workspace) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="workspace-action workspace-action--enable">Enable</button>
                                        </form>
                                        @elseif ($workspace->approval_status === 'pending')
                                        <form method="POST"
                                            action="{{Route('admin.destroy',$workspace)}}"
                                            onsubmit="return confirm('Disable this workspace?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="workspace-action workspace-action--disable">Disable</button>
                                        </form>
                                        @elseif ($workspace->approval_status === 'approved')
                                        <form method="POST"
                                            action="{{Route('admin.destroy',$workspace)}}"
                                            onsubmit="return confirm('Disable this workspace?')">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="workspace-action workspace-action--disable">Disable</button>
                                        </form>
                                        @endif
                                    </div>
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

                <div class="directory-pagination" id="workspace-pagination" hidden>
                    <p id="workspace-pagination-summary" aria-live="polite"></p>
                    <div class="pagination-controls" id="workspace-pagination-controls"></div>
                </div>
            </section>


        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        const searchInput = document.querySelector('#workspace-search');
        const statusFilter = document.querySelector('#workspace-status');
        const resetFiltersButton = document.querySelector('#reset-workspace-filters');
        const emptyRow = document.querySelector('#workspace-empty');
        const workspaceRows = document.querySelectorAll('[data-workspace-status]');
        const pagination = document.querySelector('#workspace-pagination');
        const paginationSummary = document.querySelector('#workspace-pagination-summary');
        const paginationControls = document.querySelector('#workspace-pagination-controls');
        const pageSize = 5;
        let currentPage = 1;

        function filterWorkspaces(resetPage = true) {
            if (resetPage) currentPage = 1;

            const search = searchInput.value.trim().toLocaleLowerCase();
            const status = statusFilter.value;
            const matchingRows = [];

            for (const row of workspaceRows) {
                const workspaceSearchFields = [row.querySelector('.workspace-details-link span span').textContent, row.cells[1].textContent, row.cells[3].textContent];
                const matchesSearch = workspaceSearchFields.some(value =>
                    value.toLocaleLowerCase().includes(search)
                );
                const matchesStatus = !status || row.dataset.workspaceStatus === status;

                if (matchesSearch && matchesStatus) matchingRows.push(row);
            }

            const totalPages = Math.max(1, Math.ceil(matchingRows.length / pageSize));
            currentPage = Math.min(currentPage, totalPages);
            const firstIndex = (currentPage - 1) * pageSize;
            const visibleRows = matchingRows.slice(firstIndex, firstIndex + pageSize);

            for (const row of workspaceRows) {
                row.hidden = !visibleRows.includes(row);
            }

            emptyRow.hidden = matchingRows.length > 0;
            renderPagination(matchingRows.length, totalPages, firstIndex);
        }

     function renderPagination(totalResults, totalPages, firstIndex) {
    paginationControls.replaceChildren();
    pagination.hidden = totalResults === 0;

    if (totalResults === 0) return;

    paginationSummary.textContent = `Page ${currentPage} of ${totalPages}`;

    const addButton = (label, page, disabled = false, current = false) => {
        const button = document.createElement('button');

        button.type = 'button';
        button.className = `page-button${current ? ' is-current' : ''}${disabled ? ' is-disabled' : ''}`;
        button.textContent = label;
        button.disabled = disabled;

        button.setAttribute(
            'aria-label',
            label === '‹' ? 'Previous page' :
            label === '›' ? 'Next page' :
            `Page ${label}`
        );

        if (!disabled) {
            button.addEventListener('click', () => {
                currentPage = page;
                filterWorkspaces(false);
            });
        }

        paginationControls.append(button);
    };

    addButton('‹', currentPage - 1, currentPage === 1);

    for (let page = 1; page <= totalPages; page++) {
        addButton(String(page), page, false, page === currentPage);
    }

    addButton('›', currentPage + 1, currentPage === totalPages);
}

        resetFiltersButton.addEventListener('click', () => {
            searchInput.value = '';
            statusFilter.value = '';
            filterWorkspaces();
        });
        searchInput.addEventListener('input', () => filterWorkspaces());
        statusFilter.addEventListener('change', () => filterWorkspaces());
        filterWorkspaces();
    </script>
@endpush
@endif
