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
    <style>
        /* NexOra | Admin Manage Users — page-scoped styling */
        .nx-page-content {
            --nx-primary: #315C4D;
            --nx-primary-hover: #274B3F;
            --nx-text: #1D2924;
            --nx-muted: #66766D;
            --nx-border: #E2E8E2;
            --nx-bg: #F5F7F3;
            background: var(--nx-bg);
            color: var(--nx-text);
            font-family: 'Inter', 'Noto Sans Arabic', sans-serif;
            min-height: 100%;
        }
        .nx-page-content .container { max-width: 1220px; }
        .nx-page-content h2, .nx-page-content h5 {
            font-family: 'Manrope', 'Noto Sans Arabic', sans-serif;
            color: #152A23;
            letter-spacing: -.025em;
        }
        .nx-page-content h2 { font-size: clamp(1.55rem, 2.8vw, 2rem); }
        .nx-page-content .text-muted { color: var(--nx-muted) !important; }
        .nx-page-content .card {
            background: #fff;
            border: 1px solid var(--nx-border) !important;
            border-radius: 18px;
            box-shadow: 0 5px 24px rgba(21,42,35,.045) !important;
            overflow: hidden;
        }
        .nx-page-content .card-body { padding: clamp(18px, 2.5vw, 28px); }
        .nx-page-content .form-label {
            color: #34483D;
            font-weight: 650;
            font-size: .83rem;
            margin-bottom: 8px;
        }
        .nx-page-content .form-control, .nx-page-content .form-select {
            min-height: 45px;
            border: 1px solid #D5DFD6;
            border-radius: 11px;
            background-color: #fff;
            color: var(--nx-text);
            font-size: .88rem;
            box-shadow: none;
        }
        .nx-page-content .form-control::placeholder { color: #89958D; }
        .nx-page-content .form-control:focus, .nx-page-content .form-select:focus {
            border-color: var(--nx-primary);
            box-shadow: 0 0 0 3px rgba(49,92,77,.13);
        }
        .nx-page-content .btn {
            border-radius: 999px;
            min-height: 42px;
            padding: 9px 19px;
            font-family: 'Manrope', 'Noto Sans Arabic', sans-serif;
            font-size: .85rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background-color .2s, border-color .2s, color .2s, transform .2s;
        }
        .nx-page-content .btn:hover:not(:disabled) { transform: translateY(-1px); }
        .nx-page-content .btn:focus-visible { outline: 2px solid #C58A62; outline-offset: 3px; }
        .nx-page-content .btn-primary {
            background: var(--nx-primary);
            border-color: var(--nx-primary);
            color: #fff;
        }
        .nx-page-content .btn-primary:hover { background: var(--nx-primary-hover); border-color: var(--nx-primary-hover); color: #fff; }
        .nx-page-content .btn-outline-secondary, .nx-page-content .btn-outline-primary {
            color: var(--nx-primary);
            border-color: #C9D7CD;
            background: #fff;
        }
        .nx-page-content .btn-outline-secondary:hover, .nx-page-content .btn-outline-primary:hover {
            color: #fff;
            background: var(--nx-primary);
            border-color: var(--nx-primary);
        }
        .nx-page-content .btn-outline-danger {
            color: #A92C29;
            border-color: #EBC7C4;
            background: #FFF9F8;
        }
        .nx-page-content .btn-outline-danger:hover:not(:disabled) {
            background: #B42318;
            border-color: #B42318;
            color: #fff;
        }
        .nx-page-content .btn:disabled { opacity: .48; cursor: not-allowed; }
        .nx-page-content .btn-sm { min-height: 35px; padding: 6px 14px; font-size: .79rem; }
        .nx-page-content .table-responsive { border: 1px solid #E9EEE9; border-radius: 13px; }
        .nx-page-content .table { margin-bottom: 0; color: var(--nx-text); font-size: .88rem; }
        .nx-page-content .table > :not(caption) > * > * {
            padding: 16px 17px;
            border-bottom-color: #EDF1ED;
            vertical-align: middle;
        }
        .nx-page-content .table thead th {
            background: #F6F9F5;
            color: #53615A;
            text-transform: uppercase;
            font-size: .73rem;
            letter-spacing: .055em;
            font-weight: 750;
            white-space: nowrap;
        }
        .nx-page-content .table tbody tr:hover > * { background: #FAFCF9; }
        .nx-page-content .table tbody tr:last-child td { border-bottom: 0; }
        .nx-page-content .rounded-circle.bg-primary-subtle {
            background: #E7EEE7 !important;
            color: #315C4D !important;
            flex-shrink: 0;
        }
        .nx-page-content .badge {
            border-radius: 999px;
            padding: 7px 11px;
            font-size: .74rem;
            font-weight: 700;
            letter-spacing: .01em;
        }
        .nx-page-content .badge.bg-info-subtle { background: #E7EEE7 !important; color: #315C4D !important; }
        .nx-page-content .badge.bg-success-subtle { background: #E8F5EC !important; color: #17643E !important; }
        .nx-page-content .badge.bg-danger-subtle { background: #FDECEA !important; color: #A52B26 !important; }
        .nx-page-content .pagination { gap: 5px; flex-wrap: wrap; }
        .nx-page-content .page-link {
            color: var(--nx-primary);
            border: 1px solid #DCE6DC;
            border-radius: 9px !important;
            min-width: 36px;
            text-align: center;
        }
        .nx-page-content .page-link:hover { background: #EAF0EA; color: #203D34; }
        .nx-page-content .page-item.active .page-link { background: var(--nx-primary); border-color: var(--nx-primary); color: #fff; }
        .nx-page-content .page-link:focus { box-shadow: 0 0 0 3px rgba(49,92,77,.15); }
        @media (max-width: 767px) {
            .nx-page-content .container { padding-inline: 14px; }
            .nx-page-content .card-body { padding: 18px; }
            .nx-page-content .table { min-width: 720px; }
            .nx-page-content .d-flex.gap-2 { flex-wrap: wrap; }
        }
    </style>
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
                                        <small class="text-muted">User ID: {{ $user->id }}</small>
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
          <div class="d-flex justify-content-center mt-3">
                 {{ $users->links('pagination::bootstrap-5') }}
         </div>

        </div>
    </div>

</div>
</div>
@endsection
