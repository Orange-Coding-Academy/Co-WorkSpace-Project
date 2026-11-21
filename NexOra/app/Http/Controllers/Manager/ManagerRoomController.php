<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Workspace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
class ManagerRoomController extends Controller
{
    // Display Workspace Details and its Rooms
   public function index($workspaceId)
{
    $workspace = Workspace::where('manager_id', auth()->id())->with([
        'images' => fn($q) => $q->orderByDesc('is_primary')
                               ->orderBy('sort_order'),

        'rooms' => fn($q) => $q->where('is_active', true)
                               ->with('images')
                               ->latest()
    ])->findOrFail($workspaceId);

    $rooms = $workspace->rooms;

    return view('Manager.WorkspaceDetails', compact('workspace', 'rooms'));
}

    // Add New Room
    public function store(Request $request, $workspaceId)
    {
        $workspace = Workspace::where('manager_id', auth()->id())
            ->findOrFail($workspaceId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'room_type' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
            'hourly_price' => 'required|numeric|min:0',
            'facilities' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        DB::transaction(function () use ($request, $validated, $workspace) {

            $room = $workspace->rooms()->create([
                'name' => $validated['name'],
                'room_type' => $validated['room_type'],
                'capacity' => $validated['capacity'],
                'hourly_price' => $validated['hourly_price'],
                'facilities' => $this->formatFacilities(
                    $validated['facilities'] ?? ''
                ),
                'is_active' => true,
            ]);

            foreach ($request->file('images', []) as $index => $image) {

                $path = $image->store('rooms', 'public');

                $room->images()->create([
                    'image_path' => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        });

        return back()->with('success', 'Room added successfully.');
    }

    // Update Room
    public function update(Request $request, $roomId)
    {
        $room = Room::whereHas('workspace', function ($query) {
            $query->where('manager_id', auth()->id());
        })
        ->where('is_active', true)
        ->with('images')
        ->findOrFail($roomId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'room_type' => 'required|string|max:100',
            'capacity' => 'required|integer|min:1',
            'hourly_price' => 'required|numeric|min:0',
            'facilities' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'remove_images' => 'nullable|array',
            'remove_images.*' => 'integer',
            'primary_image_id' => 'nullable|integer',
        ]);

        $removeIds = $validated['remove_images'] ?? [];

        if (!empty($removeIds)) {
            $validIds = $room->images()
                ->whereIn('id', $removeIds)
                ->pluck('id')
                ->all();

            if (count($validIds) !== count(array_unique($removeIds))) {
                return back()->withErrors([
                    'remove_images' => 'Invalid room image selected.'
                ]);
            }
        }

        if (!empty($validated['primary_image_id'])) {
            $exists = $room->images()
                ->whereKey($validated['primary_image_id'])
                ->whereNotIn('id', $removeIds)
                ->exists();

            if (!$exists) {
                return back()->withErrors([
                    'primary_image_id' => 'Invalid primary image.'
                ]);
            }
        }

        $pathsToDelete = [];

        DB::transaction(function () use (
            $request,
            $validated,
            $room,
            $removeIds,
            &$pathsToDelete
        ) {
            $room->update([
                'name' => $validated['name'],
                'room_type' => $validated['room_type'],
                'capacity' => $validated['capacity'],
                'hourly_price' => $validated['hourly_price'],
                'facilities' => $this->formatFacilities(
                    $validated['facilities'] ?? ''
                ),
            ]);

            if (!empty($removeIds)) {
                $pathsToDelete = $room->images()
                    ->whereIn('id', $removeIds)
                    ->pluck('image_path')
                    ->all();

                $room->images()->whereIn('id', $removeIds)->delete();
            }

            if (!empty($validated['primary_image_id'])) {
                $room->images()->update(['is_primary' => false]);

                $room->images()
                    ->whereKey($validated['primary_image_id'])
                    ->update(['is_primary' => true]);
            }

            $lastOrder = $room->images()->max('sort_order') ?? -1;

            foreach ($request->file('images', []) as $index => $image) {
                $path = $image->store('rooms', 'public');

                $hasPrimary = $room->images()
                    ->where('is_primary', true)
                    ->exists();

                $room->images()->create([
                    'image_path' => $path,
                    'is_primary' => !$hasPrimary && $index === 0,
                    'sort_order' => $lastOrder + $index + 1,
                ]);
            }

            // Make first image primary if no primary remains
            if (!$room->images()->where('is_primary', true)->exists()) {
                $firstImage = $room->images()
                    ->orderBy('sort_order')
                    ->first();

                $firstImage?->update(['is_primary' => true]);
            }
        });

        if (!empty($pathsToDelete)) {
            Storage::disk('public')->delete($pathsToDelete);
        }

        return back()->with('success', 'Room updated successfully.');
    }

    // Deactivate Room instead of permanently deleting it
    public function destroy($roomId)
    {
        $room = Room::whereHas('workspace', function ($query) {
            $query->where('manager_id', auth()->id());
        })
        ->where('is_active', true)
        ->findOrFail($roomId);

        $hasBookings = $room->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('end_date_time', '>', now())
            ->exists();

        if ($hasBookings) {
            return back()->with(
                'error',
                'This room cannot be deactivated because it has active or upcoming bookings.'
            );
        }

        $room->update([
            'is_active' => false
        ]);

        return back()->with(
            'success',
            'Room deactivated successfully.'
        );
    }

    // Convert comma-separated facilities into an array
    private function formatFacilities(string $facilities): array
    {
        return array_values(array_filter(
            array_map('trim', explode(',', $facilities))
        ));
    }
}
