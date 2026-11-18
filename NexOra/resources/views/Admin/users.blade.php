{{--
Route::get('userindex', [UserController::class, 'index'])->name('admin.users.index');

Route::put('deactivate/{id}', [UserController::class, 'deactivate'])->name('admin.users.deactivate');
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | NexOra</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

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
                            placeholder="Search by name">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select">
                            <option value="">All Roles</option>
                            <option value="member">Member</option>
                            <option value="spaceManager">Space Manager</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Sort By</label>
                        <select name="sortBy" class="form-select">
                            <option value="name">Name</option>
                            <option value="created_at">Date Joined</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">Direction</label>
                        <select name="sortDirection" class="form-select">
                            <option value="asc">Ascending</option>
                            <option value="desc">Descending</option>
                        </select>
                    </div>

                </div>

                <div class="mt-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Apply Filters
                    </button>

                    <a href="" class="btn btn-outline-secondary">
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
                <span class="text-muted">Total Users: --</span>
            </div>

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
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>

                               <form action="{{ route('admin.users.deactivate', $user->id) }}" method="POST" class="d-inline">
                                             @csrf
                                            @method('PUT')
                                              <button type="submit"  class="btn btn-sm btn-outline-danger"
                                                         {{ $user->is_active == 0 ? 'disabled' : '' }}>
                                                     Delete
                                               </button>
                                   </form>
                            </td>
                        </tr>
                        @endforeach
                         

                    </tbody>

                </table>
            </div>

            <!-- Pagination will be added here using Blade -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <small class="text-muted">Showing users</small>
                <div>
                    <!-- Laravel pagination links -->
                </div>
            </div>

        </div>
    </div>

</div>

</body>
</html>
