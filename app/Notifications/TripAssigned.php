<?php

namespace App\Notifications;

use App\Models\Trip;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TripAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Trip $trip
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Trip Assigned: ' . $this->trip->trip_number)
            ->line('You have been assigned to a new trip.')
            ->line('Trip Number: ' . $this->trip->trip_number)
            ->line('Destination: ' . $this->trip->destination_from . ' → ' . $this->trip->destination_to)
            ->line('Date: ' . $this->trip->trip_date->format('Y-m-d'))
            ->action('View Trip', route('trips.show', $this->trip))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'trip_id' => $this->trip->id,
            'trip_number' => $this->trip->trip_number,
            'message' => 'You have been assigned to trip: ' . $this->trip->trip_number,
        ];
    }
}
