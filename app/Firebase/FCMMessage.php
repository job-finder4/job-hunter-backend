<?php

namespace App\Firebase;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FCMMessage
{
    protected $title = "";
    protected $body = "";
    protected $endpoint;
    protected $server_key;

    public function __construct()
    {
        $this->endpoint = $this->endpoint = "https://fcm.googleapis.com/fcm/send";
        $this->server_key = env("FCM_SERVER_KEY");;
        return $this;
    }

    public function body($body)
    {
        $this->body = $body;
        return $this;
    }

    public function title($title)
    {
        $this->title = $title;
        return $this;
    }

    public function send($notifiable)
    {
        $headers = [
            'Authorization' => 'key=' . $this->server_key,
            'Content-Type' => 'application/json',
        ];
        $fields = [
            'to' => "/topics/" . 'users.' . $notifiable->id,
            'content-available' => true,
            'priority' => 'high',
            'notification' => [
                "body" => $this->body,
                "title" => $this->title,
                "sound" => "default"
            ],
        ];

        $fields = json_encode($fields);
        $client = new Client();

        try {
            $request = $client->post($this->endpoint, [
                'headers' => $headers,
                "body" => $fields,
            ]);
            $response = $request->getBody();
            Log::debug($response->getContents());
            Log::debug("e".$notifiable->id);
            return $response;

        } catch (Exception $e) {
            return $e;
        }
    }
}
