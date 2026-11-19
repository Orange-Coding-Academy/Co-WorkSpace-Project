
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details | NexOra</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .profile-avatar {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #e9ecef;
        }

        .detail-label {
            font-size: 13px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .detail-value {
            font-size: 16px;
            font-weight: 500;
            overflow-wrap: anywhere;
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
            color: #0d6efd;
        }
    </style>
</head>

<body>

<div class="container py-5">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">User Details</h2>
            <p class="text-muted mb-0">
                View user profile and account information.
            </p>
        </div>

        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            Back to Users
        </a>
    </div>

    <!-- Profile Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">

            <div class="d-flex align-items-center gap-4 flex-wrap">

            
@if ($user->avatar)
    <img
        src="{{ asset('storage/' . $user->avatar) }}"
        alt="User Avatar"
        class="profile-avatar">
@else
    <div class="profile-avatar d-flex align-items-center justify-content-center bg-light">
        <i class="bi bi-person-fill fs-1 text-secondary"></i>
    </div>
@endif


                <div>
                    <h4 class="fw-bold mb-2">{{ $user->name }}</h4>

                    <p class="text-muted mb-2">
                        {{ $user->email }}
                    </p>

                    <span class="badge bg-primary me-2">
                        {{ $user->role}}
                    </span>

                   @if ($user->is_active == 1)
    <span class="badge bg-success">Active</span>
@else
    <span class="badge bg-danger">Inactive</span>
@endif
                </div>

            </div>

        </div>
    </div>

    <!-- Personal Information -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">

            <h5 class="section-title">Personal Information</h5>

            <div class="row g-4">

                <div class="col-md-6">
                    <div class="detail-label">Full Name</div>
                    <div class="detail-value">{{ $user->name }}</div>
                </div>

                <div class="col-md-6">
                    <div class="detail-label">Email Address</div>
                    <div class="detail-value">{{ $user->email }}</div>
                </div>

                <div class="col-md-6">
                    <div class="detail-label">Phone Number</div>
<div class="detail-value">{{ $user->phone ?? 'Not Provided' }}</div>                </div>

                <div class="col-md-6">
                    <div class="detail-label">User ID</div>
                    <div class="detail-value">{{ $user->id }}</div>
                </div>

            </div>

        </div>
    </div>

    <!-- Account Information -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">

            <h5 class="section-title">Account Information</h5>

            <div class="row g-4">

                <div class="col-md-6">
                    <div class="detail-label">Account Role</div>
                    <div class="detail-value">{{ $user->role }}</div>
                </div>

                <div class="col-md-6">
                    <div class="detail-label">Account Status</div>
                    <span class="badge bg-success">{{ $user->is_active }}</span>
                </div>

                <div class="col-md-6">
                    <div class="detail-label">Registration Date</div>
                    <div class="detail-value">{{ $user->created_at }}</div>
                </div>

                <div class="col-md-6">
                    <div class="detail-label">Email Verification</div>
                    <span class="badge bg-success">{{ $user->email_verified_at }}</span>
                </div>

            </div>

        </div>
    </div>

    <!-- Member Statistics -->
     @if ($user->role=='member')
     
    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">

            <h5 class="section-title">Member Statistics</h5>

            <div class="row g-3">

                <div class="col-md-6">
                    <div class="border rounded-3 p-4 text-center">
                        <div class="stat-number">{{ $totalBookings}}</div>
                        <div class="text-muted">Total Bookings</div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="border rounded-3 p-4 text-center">
                        <div class="stat-number">{{ $totalMemberships }}</div>
                        <div class="text-muted">Total Memberships</div>
                    </div>
                </div>

            </div>

        </div>
    </div>
   
    @elseif ($user->role=='spaceManager')
     

    <!-- Space Manager Statistics -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">

            <h5 class="section-title">Space Manager Statistics</h5>

            <div class="border rounded-3 p-4 text-center">
                <div class="stat-number">{{ $totalWorkspaces }}</div>
                <div class="text-muted">Managed Workspaces</div>
            </div>

        </div>
    </div>
    @endif



     @if ($user->role=='member')


<!-- Recent User Records -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <!-- Recent Bookings -->
        <h5 class="section-title">Recent Bookings</h5>

        <div class="table-responsive mb-4">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Booking ID</th>
                        <th>Status</th>
                        <th>Booking Date</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Booking data here -->
                     @foreach ($recentBookings as  $recentBooking )
                     
                    <tr>

                        <td>{{ $recentBooking->id }}</td>
                        <td>{{ $recentBooking->status }}</td>
                        <td>{{ $recentBooking->created_at }}</td>
                    </tr>
                   @endforeach

                   @if ($recentBookings->isempty())
                   <tr>
                   <td colspan="3" class="text-muted py-4">
                         No recent bookings available.
                   </td>
                   </tr>
                   
                   @endif

                </tbody>
            </table>
        </div>

        <!-- Recent Memberships -->
        <h5 class="section-title">Recent Memberships</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Membership ID</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recentMemberships as  $recentMembership )
                     
                    <tr>

                        <td>{{ $recentMembership->id }}</td>
                        <td>{{ $recentMembership->created_at }}</td>
                    </tr>
                   @endforeach

                    <!-- Membership data here -->
                    @if ($recentMemberships->isempty())
                    <tr>
                        <td colspan="2" class="text-muted py-4">
                            No recent memberships available.
                        </td>
                    </tr>
                    @endif

                    
                </tbody>
            </table>
        </div>

    </div>
</div>


<!-- Recent Payments -->
<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">

        <h5 class="fw-bold mb-3">Recent Payments</h5>

        <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>Payment ID</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>

                   
<tbody>
    @foreach ($recentPayments as $payment)
        <tr>
            <td>#{{ $payment->id }}</td>

            <td>
                {{ number_format($payment->amount, 2) }}
                {{ $payment->currency }}
            </td>

            <td>
                        @if ($payment->status == 'paid')
                             <span class="badge bg-success">Paid</span>

                          @elseif ($payment->status == 'pending')
                               <span class="badge bg-warning text-dark">Pending</span>

                           @elseif ($payment->status == 'failed')
                                  <span class="badge bg-danger">Failed</span>

                                  @endif
            </td>

            <td>{{ $payment->created_at->format('Y-m-d') }}</td>
        </tr>
    @endforeach

    @if ($recentPayments->isEmpty())
        <tr>
            <td colspan="4" class="text-center text-muted py-4">
                No payments found.
            </td>
        </tr>
    @endif
</tbody>



            </table>
        </div>

    </div>
</div>


@elseif ($user->role=='spaceManager')

<!-- Recent Managed Workspaces -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">

        <h5 class="section-title">Recent Managed Workspaces</h5>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle text-center">
                <thead class="table-light">
                    <tr>
                        <th>Workspace ID</th>
                        <th>Workspace Name</th>
                        <th>Created At</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($recentWorkspaces as $recentWorkspace)
                        <tr>
                            <td>{{ $recentWorkspace->id }}</td>
                            <td>{{ $recentWorkspace->name }}</td>
                            <td>{{ $recentWorkspace->created_at }}</td>
                        </tr>
                    @endforeach

                    @if ($recentWorkspaces->isEmpty())
                        <tr>
                            <td colspan="3" class="text-muted py-4">
                                No managed workspaces available.
                            </td>
                        </tr>
                    @endif
                </tbody>

            </table>
        </div>

    </div>
</div>

@endif




</div>


</body>
</html>
