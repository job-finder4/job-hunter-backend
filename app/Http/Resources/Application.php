<?php

namespace App\Http\Resources;

use  Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\User as UserResource;
use App\Http\Resources\Jobad as JobadResource;
use App\Http\Resources\Cv as CvResource;

class Application extends JsonResource
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
                'type' => 'applications',
                'id' => $this->id,
                'attributes' => [
                    'user' => new UserResource($this->user),
                    'jobad' => new JobadResource($this->jobad),
                    'cv' => new CvResource($this->cv),
                    'status' => $this->status,
                    'applied_at' => $this->updated_at->toFormattedDateString(),
                ]
            ]
        ];
    }
}
