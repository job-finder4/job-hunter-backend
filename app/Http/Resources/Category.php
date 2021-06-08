<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Category extends JsonResource
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
            'data'=>[
                'type' => 'categories',
                'id' => $this->id,
                'attributes' => [
                    'name' => $this->name,
                ]
            ]
        ];
    }
}
