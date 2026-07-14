<?php

namespace App\Services;

use App\Models\Message;
use App\Models\User;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;

class ChatService
{
    public function getMessages()
    {
        return Message::with('sender')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function sendMessage(?string $content, $audio = null, ?string $audioPath = null): Message
    {
        $path = null;
        if ($audio) {
            $path = $audio->store('audio-messages', 'public');
        } elseif ($audioPath) {
            $path = $audioPath;
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'content' => $content,
            'audio_path' => $path,
            'type' => $path ? 'audio' : 'text',
        ]);

        $message->load('sender');

        event(new MessageSent($message));

        return $message;
    }

    public function getUsers()
    {
        return User::where('id', '!=', Auth::id())->get();
    }
}
