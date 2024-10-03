<?php

namespace App\Events\Role;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoleCreatedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $role;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($role)
    {
        $this->role = $role;
    }

    /** @return \Illuminate\Broadcasting\Channel|array */
    public function broadcastOn()
    {
        return new PrivateChannel('App.Models.Role');
    }

    public function broadcastAs()
    {
        return 'created';
    }
}
