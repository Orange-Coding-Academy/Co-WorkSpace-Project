<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Room;

class RoomDetailsController extends Controller
{
    public function show(Room $room)
    {
        $room = Room::with([
            'workspace',
            'images' => function ($query) {
                $query->orderByDesc('is_primary')
                      ->orderBy('sort_order');
            }
        ])
        ->where('is_active', true)
        ->whereHas('workspace', function ($query) {
            $query->where('is_active', true)
                  ->where('approval_status', 'approved');
        })
        ->findOrFail($room->id);

        return view('public.room-details', compact('room'));
    }
}
