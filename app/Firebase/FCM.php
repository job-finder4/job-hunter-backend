<?php
namespace App\Firebase;
use GuzzleHttp\Client;

class FCM{

//    protected $endpoint;
//    protected $topic;
//    protected $data;
//    protected $notification;
//
//    public function __construct()
//    {
//        $this->endpoint = "https://fcm.googleapis.com/fcm/send";
//    }
//
//    public function setEndPoint($endpoint){
//        //if there is a case to override endpoint
//        $this->endpoint = $endpoint;
//    }
//
//    public function data(array $data=[]){
//        $this->data = $data;
//    }
//    public function setTopic($topic){
//        $this->topic=$topic;
//    }
//    public function setNotification($notification)
//    {
//        $this->notification = $notification;
//    }
    public static function send($topic,$notification){
        $server_key = env("FCM_SERVER_KEY");

        $headers = [
            'Authorization' => 'key='.$server_key,
            'Content-Type'  => 'application/json',
        ];
        $fields = [
            'to'=>"/topics/" . $topic,
            'content-available' => true,
            'priority' => 'high',
//            'data' => $this->data,
            'notification'=>$notification,
        ];

        $fields = json_encode ( $fields );
        $client = new Client();

        $endpoint = "https://fcm.googleapis.com/fcm/send";
        try{
            $request = $client->post($endpoint,[
                'headers' => $headers,
                "body" => $fields,
            ]);
            $response = $request->getBody();
            return $response;
        }
        catch (Exception $e){
            return $e;
        }
    }

}
?>
