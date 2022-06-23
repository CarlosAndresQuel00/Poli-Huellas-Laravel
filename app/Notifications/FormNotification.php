<?php

namespace App\Notifications;

use App\Models\Form;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FormNotification extends Notification
{
    use Queueable;
    public $form;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($form)
    {
        $this->form = $form;
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
            'type' => 'new_form',
            'form_id' => $this->form->id,
            'user_id' => $this->form->user_id,
            'pet_id' => $this->form->pet_id,
            'time' => Carbon::now()->diffForHumans(),
        ];
    }
}
