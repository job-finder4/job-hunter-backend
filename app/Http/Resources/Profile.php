<?php

namespace App\Http\Resources;

use App\Http\Resources\JobPreference as JobPreferenceResource;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Profile extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
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
                    'skills' => new SkillCollection($this->user->skills),
                    'last_update' => $this->updated_at->toFormattedDateString(),
                    'visible' => $this->visible,
                ]
            ],
            'links' => [
                'self' => '/api/users/' . $this->user_id . '/profile'
            ],
            'included' => [
                'job_preference' => $this->whenLoaded('user', new JobPreferenceResource($this->user->jobPreference)),
                'user' => new UserResource($this->user),
            ]
        ];
    }
}
