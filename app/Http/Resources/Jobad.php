<?php

namespace App\Http\Resources;

use Carbon\Carbon;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Report as ReportResource;

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
                    'exact_expiration_date' => $this->expiration_date->format('M d Y'),
                    'skills' => new SkillCollection($this->skills),
                    'applied_at' => $this
                        ->when(auth()->check() && auth()->user()->hasRole('jobSeeker'),
                            function () {
                                return optional(
                                    optional(
                                        auth()->user()->applications()->where('jobad_id', $this->id)
                                            ->first()
                                    )->updated_at
                                )->toFormattedDateString();
                            }),
                    'approved_at' => optional($this->approved_at)->toFormattedDateString(),
                    'applied' => $this->applications()->count(),
                    'category' => new Category($this->category),
                    'refusal_report' => $this->when(auth()->check() && !auth()->user()->hasRole('jobSeeker')&&$this->refusal_report&&!$this->approved_at,
                        function () {
                            return new ReportResource($this->refusal_report);
                    }),
                ]
            ],

            'links' => [
                'self' => '/api/jobads/' . $this->id
            ]
        ];
    }
}
