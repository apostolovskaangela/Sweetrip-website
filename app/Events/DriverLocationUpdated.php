<?php

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class DriverLocationUpdated implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $driverId,
        public float $latitude,
        public float $longitude,
        public ?string $name = null
    ) {}

    public function broadcastOn()
    {
        return new Channel('drivers.live');
    }

    public function broadcastAs()
    {
        return 'driver_location_update';
    }

    public function broadcastWith()
    {
        return [
            'driverId' => $this->driverId,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'name' => $this->name,
        ];
    }
}

