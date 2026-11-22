<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\Workspace;

class LandingPageController extends Controller
{
    public function index()
    {
        // Get approved and active workspaces
        $workspaces = Workspace::query()
            ->where('approval_status', 'approved')
            ->where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        // Load workspace images directly from the database
        foreach ($workspaces as $workspace) {
            $workspace->landing_images = DB::table('workspace_images')
                ->where('workspace_id', $workspace->id)
                ->orderByDesc('is_primary')
                ->orderBy('sort_order')
                ->get();
        }

        // Get rooms belonging to approved and active workspaces
        $rooms = DB::table('rooms')
            ->join('workspaces', 'rooms.workspace_id', '=', 'workspaces.id')
            ->where('workspaces.approval_status', 'approved')
            ->where('workspaces.is_active', true)
            ->where('rooms.is_active', true)
            ->select('rooms.*', 'workspaces.name as workspace_name')
            ->latest('rooms.created_at')
            ->take(6)
            ->get();

        // Load room images directly from the database
        foreach ($rooms as $room) {
            $room->landing_images = DB::table('room_images')
                ->where('room_id', $room->id)
                ->orderByDesc('is_primary')
                ->orderBy('sort_order')
                ->get();
        }

        return view('welcome', compact('workspaces', 'rooms'));
    }
}
