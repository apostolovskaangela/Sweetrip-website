<?php

namespace App;

enum TripStatus: string
{
    case NOT_STARTED = 'not_started';
    case IN_PROCESS = 'in_process';
    case STARTED = 'started';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match($this) {
            self::NOT_STARTED => 'Not Started',
            self::IN_PROCESS => 'In Process',
            self::STARTED => 'Started',
            self::COMPLETED => 'Completed',
        };
    }
}
