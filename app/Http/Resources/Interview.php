<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Interview extends JsonResource
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
            'id' => $this->id,
            'name' => 'job interview',
            'jobad_id' => $this->jobad_id,
            'user_id' => $this->user_id,
            'start' => $this->start_date->toDateTimeString(),
            'end' => $this->end_date->toDateTimeString(),
            'contact_info' => $this->contact_info,
            'jobSeeker' => is_null($this->user_id)?null:\App\Models\User::findOrFail($this->user_id),
            'jobad' => $this->jobad,
            'company' => $this->jobad->user,
        ];
    }
}
