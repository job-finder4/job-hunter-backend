<?php

namespace App\Notifications;

use App\Channels\FCMChannel;
use App\Firebase\FCMMessage;
use App\Models\Jobad;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RecommendedJob extends Notification
{
    use Queueable;

    public $jobad;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Jobad $jobad)
    {
        $this->jobad = $jobad;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', FCMChannel::class,'broadcast'];
//        return ['database', 'broadcast',];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url("api/jobads/{$this->jobad}");
        return (new MailMessage)
            ->subject('Newly Recommended Job')
            ->greeting('Hello!')
            ->line('We Are Recommended You To See This Job Ad')
            ->action('View Job', $url)
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
            'jobad_id' => $this->jobad->id,
            'jobad_title' => $this->jobad->title,
            'action' => "/jobs/{$this->jobad->id}",
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => [
                'jobad_id' => $this->jobad->id,
                'jobad_title' => $this->jobad->title,
                'action' => "/jobs/{$this->jobad->id}"
            ]
        ]);
    }

    public function toFCM($notifiable)
    {
        return (new FCMMessage())
            ->body($this->jobad->title)
            ->title("Our Job Recommendation :");
    }

}
