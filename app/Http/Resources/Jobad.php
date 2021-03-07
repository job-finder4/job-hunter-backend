<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Jobad extends JsonResource
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
                'type' => 'jobads',
                'id' => $this->id,
                'attributes' => [
                    'title' => $this->title,
                    'location' => $this->location,
                    'company_id' => $this->user_id,
                    'description' => $this->description,
                    'salary' => $this->salary,
                    'job_time' => $this->job_time,
                    'job_type' => $this->job_type,
                    'expiration_date' => $this->expiration_date->diffForHumans(),
                    'skills' => new SkillCollection($this->skills),
                ]
            ]
        ];
    }
}
