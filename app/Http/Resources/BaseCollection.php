<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollection extends ResourceCollection
{
    /**
     * Pagination metadata.
     *
     * @var array
     */
    protected $meta; 

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $this->meta = [
            'count' => $this->count(),
            'current_page' => $this->currentPage(),
            'from' => $this->firstItem(),
            'last_page' => $this->lastPage(),
            'per_page' => $this->perPage(),
            'to' => $this->lastItem(),
            'total' => $this->total()
        ];

        return [
            'collection' => $this->collection,
            'meta' => $this->meta
        ];

    }
}
