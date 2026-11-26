<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Booking;
use App\Models\Review;

use Carbon\CarbonImmutable;

class ManagerDashboardController extends Controller
{

    public function index(Request $request)
    {
        $currentUserId = Auth::id();

        $workspaceIds = $this->getManagerWorkspaceIds($currentUserId);

        $period = $request->query('period', '30d');
        $dateRange = $this->getDateRange($period);

        $kpis = $this->getDashboardKpis($dateRange, $workspaceIds);

        $revenueChart = $this->getRevenueChartData($dateRange, $workspaceIds);

        $bookingTrend = $this->getBookingTrendData($dateRange, $workspaceIds);

        $recentBookings = $this->getRecentBookings($workspaceIds);

        $recentReviews = $this->getRecentReviews($workspaceIds);

        $alerts = $this->getDashboardAlerts($workspaceIds);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Manager Dashboard Data Retrieved Successfully',
        //     'data' => [
        //         'period' => $period,
        //         'dateRange' => $dateRange,
        //         'kpis' => $kpis,
        //         'revenueChart' => $revenueChart,
        //         'bookingTrend' => $bookingTrend,
        //         'recentBookings' => $recentBookings,
        //         'recentReviews' => $recentReviews,
        //         'alerts' => $alerts,
        //     ]
        // ], 200);

        return view('Manager.Dashboard', compact(
            'period',
            'dateRange',
            'kpis',
            'revenueChart',
            'bookingTrend',
            'recentBookings',
            'recentReviews',
            'alerts'
        ));
    }



    private function getDashboardKpis(array $dateRange, array $workspaceIds): array
    {
        $totalWorkspaces = count($workspaceIds);

        $totalBookings = Booking::query()
            ->whereHas('room', function ($query) use ($workspaceIds) {
                $query->whereIn('workspace_id', $workspaceIds);
            })
            ->whereBetween('created_at', [
                $dateRange['start'],
                $dateRange['end']
            ])
            ->count();

        $pendingBookings = Booking::query()
            ->where('status', 'pending')
            ->whereBetween('created_at', [
                $dateRange['start'],
                $dateRange['end']
            ])
            ->whereHas('room', function ($query) use ($workspaceIds) {
                $query->whereIn('workspace_id', $workspaceIds);
            })
            ->count();

        $totalRevenue = Booking::query()
            ->whereHas('room', function ($query) use ($workspaceIds) {
                $query->whereIn('workspace_id', $workspaceIds);
            })
            ->whereBetween('created_at', [
                $dateRange['start'],
                $dateRange['end']
            ])
            ->where('status', 'completed')
            ->sum('workspace_net_amount');

        $averageRating = Review::query()
            ->whereIn('workspace_id', $workspaceIds)
            ->where('is_visible', true)
            ->avg('rating');

        return [
            'totalWorkspaces' => $totalWorkspaces,
            'totalBookings' => $totalBookings,
            'pendingBookings' => $pendingBookings,
            'totalRevenue' => $totalRevenue,
            'averageRating' => $averageRating
        ];
    }

    private function getRevenueChartData(array $dateRange, array $workspaceIds): array
    {


        $start = CarbonImmutable::parse($dateRange['start'])->startOfDay();
        $end = CarbonImmutable::parse($dateRange['end'])->startOfDay();

        $data = Booking::query()
            ->selectRaw('
            DATE(created_at) as date,
            SUM(total_price) as booking_gmv,
            SUM(workspace_net_amount) as workspace_revenue
        ')
            ->where('status', 'completed')
            ->whereBetween('created_at', [
                $start,
                $end->endOfDay()
            ])->whereHas('room', function ($query) use ($workspaceIds) {
                $query->whereIn('workspace_id', $workspaceIds);
            })
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $labels = [];
        $bookingGMV = [];
        $workspaceRevenue = [];

        for ($date = $start; $date->lte($end); $date = $date->addDay()) {

            $formattedDate = $date->toDateString();

            $dayData = $data->get($formattedDate);

            $labels[] = $formattedDate;

            $bookingGMV[] = (float) ($dayData->booking_gmv ?? 0);

            $workspaceRevenue[] = (float) ($dayData->workspace_revenue ?? 0);
        }

        return [
            'labels' => $labels,
            'bookingGMV' => $bookingGMV,
            'workspaceRevenue' => $workspaceRevenue,
        ];
    }


    private function getBookingTrendData(array $dateRange, array $workspaceIds): array
    {


        $start = CarbonImmutable::parse($dateRange['start'])->startOfDay();
        $end = CarbonImmutable::parse($dateRange['end'])->startOfDay();

        $data = Booking::query()
            ->selectRaw('
            DATE(created_at) as date,
            status,
            COUNT(*) as total
        ')
            ->whereHas('room', function ($query) use ($workspaceIds) {
                $query->whereIn('workspace_id', $workspaceIds);
            })
            ->whereBetween('created_at', [
                $start,
                $end->endOfDay()
            ])
            ->whereIn('status', [
                'pending',
                'confirmed',
                'completed',
                'cancelled'
            ])
            ->groupByRaw('DATE(created_at), status')
            ->orderBy('date')
            ->get();

        $labels = [];

        $statuses = [
            'pending' => [],
            'confirmed' => [],
            'completed' => [],
            'cancelled' => [],
        ];

        for ($date = $start; $date->lte($end); $date = $date->addDay()) {
            $labels[] = $date->toDateString();
        }

        foreach ($statuses as $status => $values) {
            $statuses[$status] = array_fill(0, count($labels), 0);
        }

        foreach ($data as $row) {
            $dateIndex = array_search($row->date, $labels);

            if ($dateIndex !== false) {
                $statuses[$row->status][$dateIndex] = (int) $row->total;
            }
        }

        return [
            'labels' => $labels,
            'pending' => $statuses['pending'],
            'confirmed' => $statuses['confirmed'],
            'completed' => $statuses['completed'],
            'cancelled' => $statuses['cancelled'],
        ];
    }


    private function getRecentBookings(array $workspaceIds): array
    {


        $bookings = Booking::query()
            ->with([
                'user:id,name',
                'room:id,workspace_id,name',
                'room.workspace:id,name',
            ])
            ->whereHas('room', function ($query) use ($workspaceIds) {
                $query->whereIn('workspace_id', $workspaceIds);
            })
            ->latest('created_at')
            ->take(5)
            ->get();

        return $bookings->map(function ($booking) {
            return [
                'bookingId' => $booking->id,
                'customerName' => $booking->user?->name,
                'workspaceName' => $booking->room?->workspace?->name,
                'roomName' => $booking->room?->name,
                'bookingDate' => $booking->created_at?->format('Y-m-d H:i'),
                'totalPrice' => (float) $booking->total_price,
                'status' => $booking->status,
            ];
        })->toArray();
    }


    private function getRecentReviews(array $workspaceIds): array
    {


        $reviews = Review::query()
            ->with([
                'user:id,name',
                'workspace:id,name',
            ])
            ->whereIn('workspace_id', $workspaceIds)
            ->where('is_visible', true)
            ->latest('created_at')
            ->take(5)
            ->get();

        return $reviews->map(function ($review) {
            return [
                'customerName' => $review->user?->name,
                'workspaceName' => $review->workspace?->name,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'createdAt' => $review->created_at?->format('Y-m-d H:i'),
            ];
        })->toArray();
    }


    private function getDashboardAlerts(array $workspaceIds): array
    {

        $pendingBookings = Booking::query()
            ->where('status', 'pending')
            ->whereHas('room', function ($query) use ($workspaceIds) {
                $query->whereIn('workspace_id', $workspaceIds);
            })
            ->count();

        $alerts = [];

        if ($pendingBookings > 0) {
            $alerts[] = [
                'type' => 'warning',
                'title' => 'Pending Bookings',
                'message' => "You have {$pendingBookings} bookings waiting for your action.",
                'count' => $pendingBookings,
            ];
        }

        return $alerts;
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


    private function getManagerWorkspaceIds(int $managerId): array
    {
        return Workspace::query()
            ->where('manager_id', $managerId)
            ->pluck('id')
            ->toArray();
    }


}