<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'status' => ['nullable', 'in:paid,pending,failed'],
            'provider' => ['nullable', 'string', 'max:100'],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'search' => ['nullable', 'string', 'max:255'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        // Dashboard statistics (all-time)
        $successfulPayments = Payment::where('status', 'paid');

        $stats = [
            'successful_payments' => (clone $successfulPayments)->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'failed_payments' => Payment::where('status', 'failed')->count(),
            'total_revenue' => (clone $successfulPayments)->sum('amount'),
        ];

        // Chart date range
        $startDate = isset($filters['date_from'])
            ? Carbon::parse($filters['date_from'])->startOfDay()
            : now()->subDays(6)->startOfDay();

        $endDate = isset($filters['date_to'])
            ? Carbon::parse($filters['date_to'])->endOfDay()
            : now()->endOfDay();

        $chartPayments = Payment::where('status', 'paid')
            ->whereNotNull('paid_at')
            ->whereBetween('paid_at', [$startDate, $endDate])
            ->selectRaw('DATE(paid_at) as date, SUM(amount) as total')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $chart = collect(
            CarbonPeriod::create(
                $startDate->copy()->startOfDay(),
                $endDate->copy()->startOfDay()
            )
        )->map(function (Carbon $date) use ($chartPayments) {
            $day = $date->format('Y-m-d');

            return [
                'date' => $day,
                'amount' => (float) ($chartPayments[$day]->total ?? 0),
            ];
        })->values();

        // Payments table with filters
        $paymentsQuery = Payment::with('user');

        $paymentsQuery->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        });

        $paymentsQuery->when($filters['provider'] ?? null, function ($query, $provider) {
            $query->where('provider', $provider);
        });

        $paymentsQuery->when($filters['date_from'] ?? null, function ($query, $date) {
            $query->whereDate('created_at', '>=', $date);
        });

        $paymentsQuery->when($filters['date_to'] ?? null, function ($query, $date) {
            $query->whereDate('created_at', '<=', $date);
        });

        $paymentsQuery->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });

                $query->orWhere('stripe_payment_intent_id', 'like', "%{$search}%")
                    ->orWhere('stripe_checkout_session_id', 'like', "%{$search}%");
            });
        });

        $payments = $paymentsQuery
            ->latest()
            ->paginate($filters['per_page'] ?? 5)
            ->withQueryString();

        return view('Admin.indexPayment', compact(
            'stats',
            'chart',
            'payments',
            'filters'
        ));
    }

    // Returns only the payment details for the modal.
    public function show(string $id)
    {
        $payment = Payment::with([
            'user',
            'booking',
            'membership',
        ])->findOrFail($id);

        return view('Admin.detailsPayment', compact('payment'));
    }
}