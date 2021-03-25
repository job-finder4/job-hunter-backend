<?php

namespace App\Http\Resources;

use Carbon\Carbon;

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
                    'company' => new User($this->user),
                    'description' => $this->description,
                    'min_salary' => $this->min_salary,
                    'max_salary' => $this->max_salary,
                    'job_time' => $this->job_time,
                    'job_type' => $this->job_type,
                    'expiration_date' => $this->expiration_date->diffForHumans(),
                    'skills' => new SkillCollection($this->skills),
                    'applied_at' => $this->when(auth()->check(), function () {
                        return optional(optional(auth()->user()->applications()->where('jobad_id', $this->id)
                            ->first())->updated_at)->toFormattedDateString();
                    }),
                    'approved_at' => optional($this->approved_at)->toFormattedDateString(),
                ]
            ],

            'links' => [
                'self' => '/api/jobads/' . $this->id
            ]
        ];
    }
}
