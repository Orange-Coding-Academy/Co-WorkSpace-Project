
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Memberships | NexOra</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #f4f7fb;
            color: #1c293b;
            font-family: Arial, sans-serif;
        }

        .container {
            max-width: 1000px;
            margin: 45px auto;
            padding: 0 20px;
        }

        .page-title {
            font-size: 29px;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #788596;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 24px;
            box-shadow: 0 5px 25px rgba(0,0,0,.04);
        }

        .membership-item {
            border: 1px solid #edf0f4;
            border-radius: 12px;
            padding: 22px;
            margin-bottom: 16px;
        }

        .membership-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .membership-header h3 {
            margin: 0;
            color: #183047;
            font-size: 18px;
        }

        .membership-status {
            background: #e0f4f2;
            color: #138f88;
            padding: 7px 13px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .membership-details {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .membership-details span {
            display: block;
            color: #788596;
            font-size: 13px;
            margin-bottom: 7px;
        }

        .membership-details strong {
            font-size: 14px;
        }

        .btn {
            display: inline-block;
            background: #119e95;
            color: white;
            padding: 12px 23px;
            border: none;
            border-radius: 9px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn:hover {
            background: #087e77;
        }

        @media (max-width: 650px) {
            .card { padding: 20px; }
            .membership-details { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>
<div class="container">

    <h1 class="page-title">My Memberships</h1>
    <p class="subtitle">
        View your workspace memberships and subscription details.
    </p>

    @if(session('success'))
        <p style="color: #138f88;">
            {{ session('success') }}
        </p>
    @endif

    @if(session('error'))
        <p style="color: #dc3545;">
            {{ session('error') }}
        </p>
    @endif

    <div class="card">

        @forelse($memberships as $membership)

            <div class="membership-item">

                <div class="membership-header">
                    <h3>
                        {{ $membership->plan?->plan_name ?? 'Unknown Plan' }}
                    </h3>

                    <span class="membership-status">
                        {{ ucfirst($membership->status) }}
                    </span>
                </div>

                <div class="membership-details">

                    <div>
                        <span>Workspace</span>
                        <strong>
                            {{ $membership->plan?->workspace?->name ?? 'N/A' }}
                        </strong>
                    </div>

                    <div>
                        <span>Price</span>
                        <strong>
                            {{ $membership->plan?->price ?? 'N/A' }} JOD
                        </strong>
                    </div>

                    <div>
                        <span>Start Date</span>
                        <strong>
                            {{ $membership->start_date?->format('M d, Y') ?? 'N/A' }}
                        </strong>
                    </div>

                    <div>
                        <span>End Date</span>
                        <strong>
                            {{ $membership->end_date?->format('M d, Y') ?? 'N/A' }}
                        </strong>
                    </div>

                </div>

                {{-- Cancel Membership Button --}}
                @if($membership->status === 'active')

                    <form action="{{ route('member.memberships.cancel', $membership->id) }}"
                          method="POST"
                          style="margin-top: 20px;">

                        @csrf
                        @method('PATCH')

                        <button type="submit"
                                class="btn"
                                onclick="return confirm('Are you sure you want to cancel this membership?')">
                            Cancel Membership
                        </button>

                    </form>

                @endif

            </div>

        @empty

            <p class="subtitle">
                You don't have any memberships yet.
            </p>

        @endforelse

    </div>

    <div class="card">

        <h2>Available Membership Plans</h2>

        <p class="subtitle">
            Explore available workspace membership plans.
        </p>

        @forelse($availablePlans as $plan)

            <div class="membership-item">

                <div class="membership-header">
                    <h3>{{ $plan->plan_name }}</h3>

                    <span class="membership-status">
                        Available
                    </span>
                </div>

                <div class="membership-details">

                    <div>
                        <span>Workspace</span>
                        <strong>
                            {{ $plan->workspace?->name ?? 'N/A' }}
                        </strong>
                    </div>

                    <div>
                        <span>Price</span>
                        <strong>{{ $plan->price }} JOD</strong>
                    </div>

                    <div>
                        <span>Duration</span>
                        <strong>{{ $plan->duration_days }} Days</strong>
                    </div>

                    <div>
                        <span>Description</span>
                        <strong>
                            {{ $plan->description ?? 'No description' }}
                        </strong>
                    </div>

                </div>

                {{-- Subscribe Button --}}
                <form action="{{ route('member.stripe.checkout.membership') }}"
                      method="POST"
                      style="margin-top: 20px;">

                    @csrf

                    <input type="hidden"
                           name="plan_id"
                           value="{{ $plan->id }}">

                    <button type="submit" class="btn">
                        Subscribe
                    </button>

                </form>

            </div>

        @empty

            <p class="subtitle">
                No membership plans are available right now.
            </p>

        @endforelse

    </div>

    <a href="{{ route('member.profile.index') }}" class="btn">
        Back to Profile
    </a>

</div>
</body>
</html>
