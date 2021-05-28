<?php
namespace App\Firebase;
use GuzzleHttp\Client;

class FCM{

    protected $endpoint;
    protected $topic;
    protected $data;
    protected $notification;

    public function __construct()
    {
        $this->endpoint = "https://fcm.googleapis.com/fcm/send";
    }

    public function setEndPoint($endpoint){
        //if there is a case to override endpoint
        $this->endpoint = $endpoint;
    }

    public function data(array $data=[]){

        $this->data = $data;

    }
    public function topic($topic){
        $this->topic=$topic;
    }
    public function notification($notification)
    {
        $this->notification = $notification;
    }
    public function send(){
//        dd($this->notification);
        $server_key = env("FCM_SERVER_KEY");

        $headers = [
            'Authorization' => 'key='.$server_key,
            'Content-Type'  => 'application/json',
        ];
        $fields = [
            'to'=>"/topics/" . $this->topic,
            'content-available' => true,
            'priority' => 'high',
//            'data' => $this->data,
            'notification'=>$this->notification,
        ];

        $fields = json_encode ( $fields );

        $client = new Client();

        try{
            $request = $client->post($this->endpoint,[
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
