<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class JobadCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'link' => [
                'self' => url('/api/jobs'),
                'first_page_url' => $this->url(1),
                'prev_page_url' => $this->previousPageUrl(),
                'next_page_url' => $this->nextPageUrl(),
            ],
            "meta" => [
                "current_page" => $this->currentPage(),
                "per_page" => $this->perPage(),
                "total" => $this->total(),
                "last_page" => $this->lastPage()
            ]
        ];
    }
}
