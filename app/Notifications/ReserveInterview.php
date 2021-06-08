<?php

namespace App\Notifications;

use App\Firebase\FCMMessage;
use App\Models\Jobad;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReserveInterview extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $jobad;
    public $message;

    public function __construct(Jobad $jobad, $message)
    {
        $this->jobad = $jobad;
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
//        return ['mail'];
    }


    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $actionUrl = "/jobs/{$this->jobad->id}/interviews";

        return (new MailMessage)
            ->line('job title:' . $this->jobad->title)
            ->line($this->message)
            ->action('interviews dashboard', $actionUrl)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

//    public function toFCM($notifiable)
//    {
//        $actionUrl = "/jobs/{$this->jobad->id}/interviews";
//
//        return (new FCMMessage())
//            ->title('interview appointment')
//            ->body('job title:' . $this->jobad->title);
//    }
}
