<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;
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
                    'user' => new UserResource($this->user),
                    'skills' => new SkillCollection($this->user->skills),
                    'last_update' => $this->updated_at->toFormattedDateString()
                ]
            ],
            'links' => [
                'self' => '/api/users/'.$this->user_id.'/profile'
            ]
        ];
    }
}
