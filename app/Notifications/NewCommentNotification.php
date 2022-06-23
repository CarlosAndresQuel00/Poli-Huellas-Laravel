<?php

namespace App\Notifications;

use App\Models\Pet;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    public $pet;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Pet $pet)
    {
        $this->pet = $pet;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'new_comment',
            'pet' => $this->pet,
            'user' => auth()->user(),
            'time' => Carbon::now()->diffForHumans(),
        ];
    }
}
