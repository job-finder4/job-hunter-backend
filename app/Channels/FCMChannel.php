<?php


namespace App\Channels;
use Illuminate\Notifications\Notification;

class FCMChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        // Remember that we created the toSms() methods in our notification class
        // Now is the time to use it.
        // In our example. $notifiable is an instance of a User that just signed up.
        $message = $notification->toFCM($notifiable);

        // Now we hopefully have a instance of a SmsMessage.
        // That we are ready to send to our user.
        // Let's do it :-)
        $message->send($notifiable);
    }
}
