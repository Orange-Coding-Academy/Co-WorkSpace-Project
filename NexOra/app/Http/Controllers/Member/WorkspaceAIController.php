<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\AI\WorkspaceAIService;
use Illuminate\Http\Request;

class WorkspaceAIController extends Controller
{
     protected WorkspaceAIService $workspaceAIService;

    public function __construct(WorkspaceAIService $workspaceAIService)
    {
        $this->workspaceAIService = $workspaceAIService;
    }

    /**
     * Show the AI assistant page.
     */
    public function index()
    {
        return view('member.workspace-ai');
    }

    /**
     * Generate room recommendations.
     */
    public function recommend(Request $request)
    {
        $validated = $request->validate([
            'attendees' => 'required|integer|min:1|max:100',

            'budget' => 'required|numeric|min:0.01',

            'equipment' => 'nullable|array',

            'equipment.*' => 'string|max:100',
        ]);

        $attendees = (int) $validated['attendees'];

        $budget = (float) $validated['budget'];

        $equipment = $validated['equipment'] ?? [];

        $result = $this->workspaceAIService->recommendRooms(
            $attendees,
            $equipment,
            $budget
        );

        return response()->json([
            'success' => true,
            'answer' => $result['answer'],
            'rooms' => $result['rooms'],
        ]);
    }
}
