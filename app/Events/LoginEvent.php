<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class LoginEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * @var \Jenssegers\Agent\Agent
     */
    protected $agent;

    /**
     * @var $ip
     */
    protected $ip;

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param $agent
     * @param $ip
     */
    public function __construct($user, $agent, $ip)
    {
        $this->user = $user;
        $this->agent = $agent;
        $this->ip = $ip;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getAgent()
    {
        return $this->agent;
    }

    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
