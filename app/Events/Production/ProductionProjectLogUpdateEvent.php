<?php

namespace App\Events\Production;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductionProjectLogUpdateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $userId;

    public $model;

    public function __construct($userId, $model)
    {
        $this->userId = $userId;
        $this->model = $model;
    }

    /**
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
