<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\MarketingCampaignMail;
use Illuminate\Support\Facades\Mail;
use App\Services\AI\ManagerAIService;
use Illuminate\Support\Facades\Log;
use Throwable;
use App\Models\User;

class ManagerAIController extends Controller
{
    protected ManagerAIService $aiService;

    public function __construct(ManagerAIService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function index()
{
    $manager = User::where('email', 'manager@test.com')->firstOrFail();

    $workspaces = $this->aiService->getManagerWorkspaces($manager);

    return view('manager.ai.index', compact('workspaces'));
}

    public function ask(Request $request)
    {
        $validated = $request->validate([
            'question' => ['required', 'string', 'max:2000'],
        ]);

        try {
        $manager = User::where('email', 'manager@test.com')->firstOrFail();

   $answer = $this->aiService->askWithManagerData(
    $manager,
    $validated['question']
);

            return response()->json([
                'success' => true,
                'answer' => $answer,
            ]);

        } catch (Throwable $e) {

            Log::error('Manager AI Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }

    public function generateMarketing(Request $request)
{
    $validated = $request->validate([
        'workspace_id' => ['required', 'integer'],
        'request' => ['required', 'string', 'max:2000'],
    ]);

    try {
       $manager = User::where('email', 'manager@test.com')->firstOrFail();

        $content = $this->aiService->generateMarketingContent(
            $manager,
            $validated['workspace_id'],
            $validated['request']
        );

        return response()->json([
            'success' => true,
            'content' => $content,
        ]);

    } catch (Throwable $e) {

        Log::error('Manager Marketing AI Error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Something went wrong. Please try again.',
        ], 500);
    }
}
public function sendMarketing(Request $request)
{
    $validated = $request->validate([
        'workspace_id' => ['required', 'integer'],
        'subject' => ['required', 'string', 'max:200'],
        'content' => ['required', 'string', 'max:10000'],
    ]);

    try {
        // Temporary test manager until Auth is implemented
        $manager = User::where('email', 'manager@test.com')->firstOrFail();

        // Make sure the workspace belongs to this manager
        $workspace = \Illuminate\Support\Facades\DB::table('workspaces')
            ->where('id', $validated['workspace_id'])
            ->where('manager_id', $manager->id)
            ->where('is_active', true)
            ->first();

        if (!$workspace) {
            return response()->json([
                'success' => false,
                'message' => 'Workspace not found.',
            ], 404);
        }

        // Get all previous confirmed/completed bookers
        $recipients = $this->aiService->getMarketingRecipients($manager);

        if ($recipients->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'There are no eligible recipients.',
            ], 422);
        }

        $sent = 0;
        $failed = 0;

        foreach ($recipients as $recipient) {
            try {
                Mail::to($recipient->email)->send(
                    (new MarketingCampaignMail(
                        $validated['content'],
                        $workspace->name
                    ))->subject($validated['subject'])
                );

                $sent++;
            } catch (Throwable $e) {
                $failed++;

                Log::error('Marketing email failed', [
                    'email' => $recipient->email,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Marketing campaign sending completed.',
            'total_recipients' => $recipients->count(),
            'sent' => $sent,
            'failed' => $failed,
        ]);

    } catch (Throwable $e) {
        Log::error('Marketing Campaign Error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Unable to send marketing campaign.',
        ], 500);
    }
}
}
