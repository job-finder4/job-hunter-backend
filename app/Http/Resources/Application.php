<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\User as UserResource;
use App\Http\Resources\Jobad as JobadResource;

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
                'type' => 'application',
                'id' => $this->id,
                'attributes' => [
                    'user' => new UserResource($this->user),
                    'jobad' => new JobadResource($this->jobad),
                    'applied_at' => $this->updated_at->toFormattedDateString(),
                ]
            ]
        ];
    }
}
