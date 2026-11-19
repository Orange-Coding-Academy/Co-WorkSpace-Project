<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Workspace;

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


    public function create() {}


    public function store(StoreRoomRequest $request) {}


    public function show(Room $room) {}


    public function edit(Room $room) {}


    public function update(UpdateRoomRequest $request, Room $room) {}


    public function destroy(Room $room) {}
}
