<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobPreference extends JsonResource
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
                'id' => $this->id,
                'type' => 'job_preferences',
                'attributes' => [
                    'user_id' => $this->user_id,
                    'job_title' => $this->job_title,
                    'job_category' => $this->job_category,
                    'salary' => $this->salary,
                    'location' => $this->location,
                    'work_type' => $this->work_type,
                ]
            ]
        ];
    }
}
