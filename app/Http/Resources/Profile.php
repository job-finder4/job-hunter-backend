<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Profile extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => [
                'type' => 'profile',
                'id' => $this->id,
                'attributes' => [
                    'details' => $this->details,
                    'user_id' => $this->user_id
                ]
            ],
            'links' => [
                'self' => '/api/user/'.$this->user_id.'/profile'
            ]
        ];
    }
}
