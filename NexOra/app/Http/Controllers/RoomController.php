<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Workspace;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RoomController extends Controller
{

    public function index($WorkSoaceID)
    {

        $rooms = Room::where('workspace_id', $WorkSoaceID)
            ->with('images')
            ->get();
        $workspace = Workspace::with('images')->findOrFail($WorkSoaceID);
            return view('Admin.WorkspaceDetails',compact(['rooms','workspace']));
    }


    public function create() {




    }


public function store(Request $request, $workspaceId)
{
    $user = $request->user();
    abort_unless($user, 403);

    $workspace = Workspace::where('manager_id', $user->id)
        ->findOrFail($workspaceId);

    $data = $request->validate([
        'name'        => 'required|string|max:255',
        'room_type'   => 'required|string|max:100',
        'capacity'    => 'required|integer|min:1',
        'hourly_price' => 'required|numeric|min:0',
        'facilities'  => 'nullable|string',
        'images'      => 'nullable|array',
        'images.*'    => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
    ]);

    $savedPaths = [];

    try {
        DB::transaction(function () use ($workspace, $data, &$savedPaths) {
            $facilities = array_map('trim', explode(',', $data['facilities'] ?? ''));

            $room = $workspace->rooms()->create([
                'name' => $data['name'],
                'room_type' => $data['room_type'],
                'capacity' => $data['capacity'],
                'hourly_price' => $data['hourly_price'],
                'facilities' => array_values(array_filter($facilities, fn (string $facility) => $facility !== '')),
                'is_active' => true,
            ]);

            $position = 0;

            foreach ($data['images'] ?? [] as $image) {


                $filename = Str::uuid() . '.' . $image->extension();

                
                $path = $image->storeAs('rooms', $filename, 'public');

                if ($path === false) {
                    throw new \RuntimeException('Could not save the room photo.');
                }

                $savedPaths[] = $path;

                $room->images()->create([
                    'image_path' => $path,
                    'alt_text' => $room->name,
                    'is_primary' => $position === 0,
                    'sort_order' => $position,
                ]);

                $position++;
            }
        });
    } catch (\Throwable $error) {
        Storage::disk('public')->delete($savedPaths);
        throw $error;
    }

    return back()->with('success', 'Room added successfully.');
}

    public function show(Room $room) {}


    public function edit(Room $room) {}


    public function update(UpdateRoomRequest $request, Room $room) {}


    public function destroy(Room $room) {}
}
