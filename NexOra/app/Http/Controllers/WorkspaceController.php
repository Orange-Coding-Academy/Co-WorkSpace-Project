<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreWorkspaceRequest;
use App\Http\Requests\UpdateWorkspaceRequest;
use App\Models\Workspace;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class WorkspaceController extends Controller
{

    public function index()
    {


        $workspaces = Workspace::with(['manager', 'rooms', 'images'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc') // newest first
            ->get();


        $enabled = $workspaces->where('is_active', true);



        $statistics = [
            'total'    => $workspaces->count(),
            'active'   => $enabled->where('approval_status', 'approved')->count(),
            'pending'  => $enabled->where('approval_status', 'pending')->count(),
            'inactive' => $workspaces->where('is_active', false)->count(),
        ];




        return view('Admin.MangeWorkSpace', compact('workspaces', 'statistics'));
    }





    public function create()
    {



        return view('Manager.createWorkSpace');
    }



    private function saveWorkspaceImage(UploadedFile $image): string
    {
        $filename = Str::uuid() . '.' . $image->extension();
        $image->move(public_path('img'), $filename);

        return 'img/' . $filename;
    }

    public function store(StoreWorkspaceRequest $request)
    {
        $data = $request->validated();
        $imagePath = $this->saveWorkspaceImage($request->file('image'));

        try {
            $workspace = DB::transaction(function () use ($data, $request, $imagePath) {


                $workspace = Workspace::create([
                    'manager_id' => $request->user()->id,
                    'name' => $data['name'],
                    'location' => $data['location'],
                    'description' => $data['description'] ?? null,
                    'facilities' => $data['facilities'] ?? [],
                    'approval_status' => 'pending',
                    'is_active' => true,
                ]);

                $workspace->images()->create([
                    'image_path' => $imagePath,
                    'alt_text' => $workspace->name,
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);

                return $workspace;
            });
        } catch (\Throwable $error) {
            File::delete(public_path($imagePath));
            throw $error;
        }

        return redirect()->route('manager.workspaces.details', [
            'workspaceId' => $workspace->id,
        ])->with('success', 'Workspace created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Workspace $workspace)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Workspace $workspace)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkspaceRequest $request, Workspace $workspace)
    {
        //
    }

    


    public function UpdateStatus(Workspace $workspace)
    {

        if ($workspace->approval_status === 'pending') {

            $workspace->approval_status = 'approved';
            $workspace->save();
            return redirect()->route('admin.workspaces')
                ->with('success', 'Workspace approved successfully.');
        } else {

            return back()->with('success', 'Already Approved');
        }
    }

    public function destroy(Workspace $workspace)
    {
        if (! $workspace->is_active) {
            return back()->with('error', 'This workspace is already disabled.');
        }

        $workspace->is_active = false;
        $workspace->save();

        return back()->with('success', 'Workspace disabled successfully.');
    }


    public function enable(Workspace $workspace)
    {
        if ($workspace->is_active) {
            return back()->with('error', 'This workspace is already enabled.');
        }

        $workspace->is_active = true;
        $workspace->save();

        return back()->with('success', 'Workspace enabled successfully.');
    }

    public function LastWorkspaces()
    {
        $workspaces = Workspace::with(['manager', 'rooms', 'images'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
dd($workspaces);
       
    
    }    
}