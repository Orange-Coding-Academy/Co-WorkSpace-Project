Route::get('/admin/workspaces', [WorkspaceController::class, 'index'])
    ->name('workspaces');

Route::patch('/admin/workspaces/{workspace}/disable', [WorkspaceController::class, 'destroy'])
    ->name('destroy');

Route::patch('/admin/workspaces/{workspace}/UpdateStatus', [WorkspaceController::class, 'UpdateStatus'])
    ->name('UpdateStatus');

Route::patch('/admin/workspaces/{workspace}/enable', [WorkspaceController::class, 'enable'])
    ->name('Enable');

Route::get('/admin/workspaces/{workspaceId}', [RoomController::class, 'index'])
    ->name('workspace.details');
    ////////////////manger route 
    Route::get('/workspaces/create', [WorkspaceController::class, 'create'])
    ->name('workspaces.create');

Route::post('/workspaces', [WorkspaceController::class, 'store'])
    ->name('workspaces.store');
