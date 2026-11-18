<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Http\Requests\StoreWorkspaceRequest;
use App\Http\Requests\UpdateWorkspaceRequest;

class WorkspaceController extends Controller
{
   
    public function index()
    {
        
        
        $workspace = Workspace::all();

         return view('',compact('workspace'));


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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workspace $workspace)
    {
        $workspace->is_active = false;
        $workspace->save();
          return redirect()->back()
        ->with('success', 'Workspace deactivated successfully.');
    }
}
