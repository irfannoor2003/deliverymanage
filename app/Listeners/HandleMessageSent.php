<?php

namespace App\Listeners;

use App\Events\MessageSent;
use App\Services\ActivityService;

class HandleMessageSent
{
    public function handle(MessageSent $event): void
    {
        app(ActivityService::class)->log(
            'chat_sent',
            "Message sent by {$event->message->sender->name}"
        );
    }
}
