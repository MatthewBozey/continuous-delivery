<?php

namespace App\Events\Production;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProjectLogUpdateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $userId;

    public $model;

    /**
     * Create a new event instance.
     */
    public function __construct($userId, $model)
    {
        $this->userId = $userId;
        $this->model = $model;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('UpdateUpdatePackage'.$this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'ProjectLogUpdated';
    }
}
