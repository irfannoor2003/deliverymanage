<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Services\ChatService;
use App\Services\ActivityService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct(
        protected ChatService $chatService,
        protected ActivityService $activityService,
    ) {}

    public function index()
    {
        $messages = $this->chatService->getMessages();
        $users = $this->chatService->getUsers();
        return view('chat.index', compact('messages', 'users'));
    }

    public function sendMessage(SendMessageRequest $request)
    {
        $message = $this->chatService->sendMessage(
            $request->content,
            $request->file('audio')
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Message sent']);
        }

        return back()->with('success', 'Message sent');
    }

    public function getMessages()
    {
        $messages = $this->chatService->getMessages();
        return response()->json($messages);
    }

    public function broadcastRecording(Request $request)
    {
        $request->validate(['recording' => 'required|boolean']);
        if ($request->recording) {
            cache(['recording_user_id' => auth()->id(), 'recording_user_name' => auth()->user()->name], now()->addSeconds(30));
        } else {
            cache()->forget('recording_user_id');
            cache()->forget('recording_user_name');
        }
        return response()->json(['ok' => true]);
    }

    public function recordingStatus()
    {
        $userId = cache('recording_user_id');
        $userName = cache('recording_user_name');
        return response()->json([
            'recording' => !is_null($userId),
            'user_id' => $userId,
            'user_name' => $userName,
        ]);
    }
}
