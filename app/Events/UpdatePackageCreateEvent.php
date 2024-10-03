<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdatePackageCreateEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $update_package;

    public function __construct($updatePackage)
    {
        $this->update_package = $updatePackage;
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('App.Models.UpdatePackage')];
    }

    public function broadcastAs(): string
    {
        return 'UpdatePackageUpdated';
    }
}
