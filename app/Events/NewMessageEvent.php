<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Message $message;
    public int $unreadCount;

    public function __construct(Message $message)
    {
        $this->message = $message;
        $this->unreadCount = Message::where('status', 'unread')->count();
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.messages'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'new-message';
    }
}
