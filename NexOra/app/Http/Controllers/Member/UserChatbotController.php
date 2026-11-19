<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\AI\UserChatbotService;
use Illuminate\Http\Request;
use Throwable;

class UserChatbotController extends Controller
{
    // private function checkMember(): void
    // {
    //     abort_unless(
    //         auth()->check() &&
    //         auth()->user()->role === 'member',
    //         403
    //     );
    // }

    private function sessionKey(): string
    {
        return 'nexora_chat_' . auth()->id();
    }

    public function index()
    {
        // $this->checkMember();

        $history = session()->get(
            $this->sessionKey(),
            []
        );

        return view('member.chatbot', compact('history'));
    }

    public function send(
        Request $request,
        UserChatbotService $chatbot
    ) {
        // $this->checkMember();

        $validated = $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        $history = session()->get(
            $this->sessionKey(),
            []
        );

        try {

            $answer = $chatbot->answer(
                auth()->user(),
                $validated['message'],
                $history
            );

            // Save user message
            $history[] = [
                'role' => 'user',
                'content' => $validated['message'],
            ];

            // Save AI response
            $history[] = [
                'role' => 'assistant',
                'content' => $answer,
            ];

            // Keep the latest 20 messages
            $history = array_slice($history, -20);

            session()->put(
                $this->sessionKey(),
                $history
            );

            return response()->json([
                'success' => true,
                'answer' => $answer,
            ]);

        } catch (Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }

    public function clear()
    {
        // $this->checkMember();

        session()->forget($this->sessionKey());

        return response()->json([
            'success' => true,
            'message' => 'Conversation cleared successfully.',
        ]);
    }
}
