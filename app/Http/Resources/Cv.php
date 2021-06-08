<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
                    'download_link' =>'/api/cvs/' . $this->id . '/download',
                    'size' => floor((Storage::disk('local')->size($this->path)/(1024)) * 100)/100 .'Kib',
                    'last_modified' => Carbon::parse(Storage::disk('local')->lastModified($this->path))->toDateString()
                ]
            ],
        ];
    }
}
