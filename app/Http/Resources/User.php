<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $role='NONE';
        if($this->getRoleNames()->isNotEmpty()){
            $role=$this->getRoleNames()[0];
        }

        return [
            'data' => [
                'type' => 'users',
                'id' => $this->id,
                'attributes' => [
                    'name' => $this->name,
                    'email' => $this->email,
                    'role'=>$role,
                    'image' => url($this->image->path),
                ]
            ]
        ];
    }
}
