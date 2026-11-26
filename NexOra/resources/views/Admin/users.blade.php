{{--
هاي لصفحة اليوزر كلهم بس بتعرضهم
Route::get('userindex', [UserController::class, 'index'])->name('admin.users.index');

هاي اذا بدي اعمل is_active لمستخدم معين
Route::put('deactivate/{id}', [UserController::class, 'deactivate'])->name('admin.users.deactivate');

هاي لصفحة التفاصيل لليوزر

Route::get('usershow/{id}', [UserController::class, 'show'])->name('admin.users.show');
--}}

@extends('layouts.dashboard')

@section('dashboard-role', 'admin')

@section('title') Manage Users | NexOra @endsection
@section('page-title') Manage Users @endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@400;500;600;700;800&family=Noto+Sans+Arabic:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/pages/design-refactor-shared-09.css') }}">
@endpush

@section('content')
<div class="nx-page-content bg-light">
<div class="container px-3 pb-3 pt-0">

    <!-- Page Header -->
    <div class="mb-4">
        <h2 class="fw-bold">Manage Users</h2>
        <p class="text-muted">View, search, filter and manage platform users.</p>
    </div>

    <!-- Search and Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="">

                <div class="row g-3 align-items-end">

                    <div class="col-md-4">
                        <label class="form-label">Search Users</label>
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            value="{{ $search }}"
                            placeholder="Search by name">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="">All Roles</option>
                            <option value="member" @selected($filter == 'member')>Member</option>
                            <option value="spaceManager" @selected($filter == 'spaceManager')>Space Manager</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="1" @selected($status == '1')>Active</option>
                            <option value="0" @selected($status == '0')>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Sort By</label>
                        <select name="sortBy" class="form-select">
                            <option value="name" @selected($sortBy == 'name') >Name</option>
                            <option value="created_at" @selected($sortBy== 'created_at')>Date Joined</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Direction</label>
                        <select name="sortDirection" class="form-select">
                            <option value="asc" @selected($sortDirection == 'asc') >Ascending</option>
                            <option value="desc"  @selected($sortDirection == 'desc')>Descending</option>
                        </select>
                    </div>

                </div>

                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Apply Filters
                    </button>

                    <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">All Users</h5>
                    <span class="text-muted">Total Users: {{ $users->total() }}</span>            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Date Joined</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                         @foreach ($users as $user)
                         
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold"
                                         style="width:42px;height:42px;">
                                        U
                                    </div>

                                    <div>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="badge bg-info-subtle text-info-emphasis">
                                    {{ $user->role }}
                                </span>
                            </td>

                           <td>
                             @if ($user->is_active == 1)
                                <span class="badge bg-success-subtle text-success">
                                  Active
                                </span>
                              @else
                                <span class="badge bg-danger-subtle text-danger">
                                 Inactive
                               </span>
                            @endif
                           </td>

                           
                            <td>{{ $user->created_at->format('M d, Y') }}</td>

                            <td class="text-end">
    <a href="{{ route('admin.users.show', $user->id) }}"
       class="btn btn-sm btn-outline-primary"
       title="View User"
       aria-label="View User">
        <i class="bi bi-eye"></i>
    </a>

    <form action="{{ route('admin.users.deactivate', $user->id) }}"
          method="POST"
          class="d-inline">
        @csrf
        @method('PUT')

        <button type="submit"
                class="btn btn-sm btn-outline-danger"
                title="Deactivate User"
                aria-label="Deactivate User"
                {{ $user->is_active == 0 ? 'disabled' : '' }}>
            <i class="bi bi-trash"></i>
        </button>
    </form>
</td>
                        </tr>
                        @endforeach
                         

                    </tbody>

                </table>
            </div>

            <!-- Pagination will be added here using Blade -->
         {{-- Custom Pagination --}}
@if($users->hasPages())
    @php
        $users->withQueryString();

        $currentPage = $users->currentPage();
        $lastPage = $users->lastPage();

        $windowStart = max(1, $currentPage - 1);
        $windowEnd = min($lastPage, $currentPage + 1);
    @endphp

    <div class="payments-pagination">

        <div class="pagination-summary">
            Page {{ $currentPage }} of {{ $lastPage }}
        </div>

        <nav aria-label="Users pagination">
            <ul class="pager">

                {{-- Previous --}}
                <li>
                    @if($users->onFirstPage())
                        <span class="pager-link is-disabled">‹</span>
                    @else
                        <a class="pager-link"
                           href="{{ $users->previousPageUrl() }}">‹</a>
                    @endif
                </li>

                {{-- First page --}}
                @if($windowStart > 1)
                    <li>
                        <a class="pager-link" href="{{ $users->url(1) }}">1</a>
                    </li>

                    @if($windowStart > 2)
                        <li>
                            <span class="pager-link is-disabled">…</span>
                        </li>
                    @endif
                @endif

                {{-- Page numbers --}}
                @foreach(range($windowStart, $windowEnd) as $page)
                    <li>
                        @if($page === $currentPage)
                            <span class="pager-link is-active">
                                {{ $page }}
                            </span>
                        @else
                            <a class="pager-link" href="{{ $users->url($page) }}">
                                {{ $page }}
                            </a>
                        @endif
                    </li>
                @endforeach

                {{-- Last page --}}
                @if($windowEnd < $lastPage)
                    @if($windowEnd < $lastPage - 1)
                        <li>
                            <span class="pager-link is-disabled">…</span>
                        </li>
                    @endif

                    <li>
                        <a class="pager-link" href="{{ $users->url($lastPage) }}">
                            {{ $lastPage }}
                        </a>
                    </li>
                @endif

                {{-- Next --}}
                <li>
                    @if($users->hasMorePages())
                        <a class="pager-link"
                           href="{{ $users->nextPageUrl() }}">›</a>
                    @else
                        <span class="pager-link is-disabled">›</span>
                    @endif
                </li>

            </ul>
        </nav>

    </div>
@endif

        </div>
    </div>

</div>
</div>
@endsection
