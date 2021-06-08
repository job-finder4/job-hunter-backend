<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobadRefused extends Notification
{
    use Queueable;

    public $jobad;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($jobad)
    {
        $this->jobad=$jobad;
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
        return ['broadcast','database'];
//        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
            'jobad_id' => $this->jobad->id,
            'jobad_title' => $this->jobad->title,
            'refusal_reason'=>$this->jobad->refusal_report->description,
            'action' => "/company/my-jobs/".$this->jobad->id,
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param mixed $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => [
                'jobad_id' => $this->jobad->id,
                'jobad_title' => $this->jobad->title,
                'refusal_reason'=>$this->jobad->refusal_report->description,
                'action' => "/myjobs/{$this->jobad->id}",
            ]
        ]);
    }
}
