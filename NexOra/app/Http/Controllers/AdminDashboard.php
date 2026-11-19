<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Workspace;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Payment;

use Carbon\CarbonImmutable;

class AdminDashboard extends Controller
{
    //
    public function index(Request $request)
    {
        $period = $request->query('period', '30d');

        $dateRange = $this->getDateRange($period);

        $kpis = $this->getDashboardKpis($dateRange);

        $bookingStatuses = $this->getBookingStatusData($dateRange);

        $revenueChart = $this->getRevenueChartData($dateRange);

        $topWorkspaces = $this->getTopWorkspaces($dateRange);

        $recentActivities = $this->getRecentActivities();

        $alerts = $this->getDashboardAlerts();

        return view('Admin.dashboard', compact(
            'kpis',
            'bookingStatuses',
            'topWorkspaces',
            'recentActivities',
            'alerts',
            'revenueChart'
        ));
    }

    public function getDashboardKpis(array $dateRange): array
    {
        return [
            'platformCommission' => Booking::sum('platform_commission_amount'),
            'bookingGMV' => Booking::sum('total_price'),
            'totalBookings' => Booking::whereBetween('created_at', [
                $dateRange['start'],
                $dateRange['end']
            ])->count(),
            'totalMembers' => User::where('role', 'member')->count(),
            'activeWorkspaces' => Workspace::where('is_active', true)
                ->where('approval_status', 'approved')
                ->count(),
        ];
    }

    public function getRevenueChartData(array $dateRange): array
    {
        $data = Booking::query()
            ->selectRaw('
            DATE(created_at) as date,
            SUM(total_price) as booking_gmv,
            SUM(platform_commission_amount) as platform_commission
        ')
            ->where('status', 'completed')
            ->whereBetween('created_at', [
                $dateRange['start'],
                $dateRange['end']
            ])
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get();

        return [
            'labels' => $data->pluck('date'),
            'bookingGMV' => $data->pluck('booking_gmv'),
            'platformCommission' => $data->pluck('platform_commission'),
        ];
    }

    public function getBookingStatusData(array $dateRange): array
    {
        $statuses = Booking::query()
            ->select('status')
            ->selectRaw('COUNT(*) as total')
            ->whereBetween('created_at', [
                $dateRange['start'],
                $dateRange['end']
            ])
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'completed' => (int) ($statuses['completed'] ?? 0),
            'confirmed' => (int) ($statuses['confirmed'] ?? 0),
            'pending' => (int) ($statuses['pending'] ?? 0),
            'cancelled' => (int) ($statuses['cancelled'] ?? 0),
        ];
    }

    public function getTopWorkspaces(array $dateRange): array
    {
        return Workspace::query()
            ->withCount([
                'bookings' => function ($query) use ($dateRange) {
                    $query->whereBetween('bookings.created_at', [
                        $dateRange['start'],
                        $dateRange['end']
                    ]);
                }
            ])
            ->withSum([
                'bookings' => function ($query) use ($dateRange) {
                    $query->whereBetween('bookings.created_at', [
                        $dateRange['start'],
                        $dateRange['end']
                    ]);
                }
            ], 'total_price')
            ->orderByDesc('bookings_count')
            ->limit(5)
            ->get()
            ->map(fn($workspace) => [
                'name' => $workspace->name,
                'totalBookings' => $workspace->bookings_count,
                'totalGMV' => $workspace->bookings_sum_total_price ?? 0,
            ])
            ->toArray();
    }

    public function getRecentActivities(): array
    {
        $limit = 10;

        $bookings = Booking::latest()
            ->take($limit)
            ->get()
            ->map(fn($booking) => [
                'type' => 'booking',
                'title' => 'New Booking',
                'description' => "Booking #{$booking->id}",
                'createdAt' => $booking->created_at,
            ]);

        $payments = Payment::latest()
            ->take($limit)
            ->get()
            ->map(fn($payment) => [
                'type' => 'payment',
                'title' => 'New Payment',
                'description' => "Payment #{$payment->id}",
                'createdAt' => $payment->created_at,
            ]);

        $users = User::latest()
            ->take($limit)
            ->get()
            ->map(fn($user) => [
                'type' => 'user',
                'title' => 'New User',
                'description' => $user->name,
                'createdAt' => $user->created_at,
            ]);

        $workspaces = Workspace::latest()
            ->take($limit)
            ->get()
            ->map(fn($workspace) => [
                'type' => 'workspace',
                'title' => 'New Workspace',
                'description' => $workspace->name,
                'createdAt' => $workspace->created_at,
            ]);

        return $bookings
            ->concat($payments)
            ->concat($users)
            ->concat($workspaces)
            ->sortByDesc('createdAt')
            ->take($limit)
            ->values()
            ->map(fn($activity) => [
                ...$activity,
                'createdAt' => $activity['createdAt']->toDateTimeString(),
            ])
            ->toArray();
    }

    public function getDashboardAlerts(): array
    {
        return [];
    }

    private function getDateRange(string $period): array
    {
        $now = CarbonImmutable::now();

        return match ($period) {
            'today' => [
                'start' => $now->startOfDay(),
                'end' => $now,
            ],

            '7d' => [
                'start' => $now->subDays(6)->startOfDay(),
                'end' => $now,
            ],

            '30d' => [
                'start' => $now->subDays(29)->startOfDay(),
                'end' => $now,
            ],

            '90d' => [
                'start' => $now->subDays(89)->startOfDay(),
                'end' => $now,
            ],

            'this_month' => [
                'start' => $now->startOfMonth(),
                'end' => $now,
            ],

            default => [
                'start' => $now->subDays(29)->startOfDay(),
                'end' => $now,
            ],
        };
    }

}