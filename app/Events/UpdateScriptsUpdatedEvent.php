<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateScriptsUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $updatePackageId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($updatePackageId)
    {
        $this->updatePackageId = $updatePackageId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.UpdatePackage.'.$this->updatePackageId);
    }

    public function broadcastAs(): string
    {
        return 'UpdateScriptUpdated';
    }
}
