<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Http\Requests\StoreWorkspaceRequest;
use App\Http\Requests\UpdateWorkspaceRequest;

class WorkspaceController extends Controller
{

    public function index()
    {


        $workspaces = Workspace::with(['manager', 'rooms','images'])->get();


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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreWorkspaceRequest $request)
    {
        //
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
            return back()->with('success', 'Workspace approved successfully.');
        } else {

            return back()->with('success', 'Already Approved');
        }
    }

    public function destroy(Workspace $workspace)
    {
        if (! $workspace->is_active) {
            return back()->with('error', 'This workspace is already disabled.');
        }

        if ($workspace->approval_status !== 'approved') {
            return back()->with('error', 'Only approved workspaces can be disabled.');
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
}
