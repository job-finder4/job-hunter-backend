<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Cv extends JsonResource
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
                'type' => 'cvs',
                'id' => $this->id,
                'attributes' => [
                    'title' => $this->title,
                    'user_id' => $this->user_id,
                    'download_link' =>'/api/cvs/' . $this->id . '/download'
                ]
            ],
        ];
    }
}
