<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Workspace;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    public function index(Request $request)
    {
        $query = Room::with([
            'workspace',
            'images' => function ($q) {
                $q->orderByDesc('is_primary')
                  ->orderBy('sort_order');
            }
        ])
        ->where('is_active', true)
        ->whereHas('workspace', function ($q) {
            $q->where('is_active', true)
              ->where('approval_status', 'approved');
        });

        // Filter by workspace
        if ($request->filled('workspace')) {
            $query->where('workspace_id', $request->workspace);
        }

        // Filter by minimum capacity
        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', $request->capacity);
        }

        // Filter by minimum price
        if ($request->filled('min_price')) {
            $query->where('hourly_price', '>=', $request->min_price);
        }

        // Filter by maximum price
        if ($request->filled('max_price')) {
            $query->where('hourly_price', '<=', $request->max_price);
        }

        // Filter by room type
        if ($request->filled('room_type')) {
            $query->where('room_type', $request->room_type);
        }

        // Filter by equipment
        if ($request->filled('equipment')) {
            $query->whereJsonContains(
                'facilities',
                $request->equipment
            );
        }

        // Search by room or workspace name
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('workspace', function ($workspaceQuery) use ($search) {
                      $workspaceQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $rooms = $query->latest()->paginate(3)->withQueryString();

        // Active approved workspaces for filter
        $workspaces = Workspace::where('is_active', true)
            ->where('approval_status', 'approved')
            ->orderBy('name')
            ->get();

        // Room types available in the database
        $roomTypes = Room::where('is_active', true)
            ->whereHas('workspace', function ($q) {
                $q->where('is_active', true)
                  ->where('approval_status', 'approved');
            })
            ->distinct()
            ->orderBy('room_type')
            ->pluck('room_type');

        // Collect equipment values from room facilities JSON
        $equipmentOptions = Room::where('is_active', true)
            ->whereHas('workspace', function ($q) {
                $q->where('is_active', true)
                  ->where('approval_status', 'approved');
            })
            ->whereNotNull('facilities')
            ->pluck('facilities')
            ->flatten()
            ->filter()
            ->unique()
            ->sort()
            ->values();

        return view('public.explore', compact(
            'rooms',
            'workspaces',
            'roomTypes',
            'equipmentOptions'
        ));
    }
}
