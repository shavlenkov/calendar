<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'resourceId' => (string) $this->resource->resourceId,
            'format' => $this->resource->format,
            'start' => $this->resource->start,
            'end' => $this->resource->end,
            'users' => UserResource::collection($this->resource->users)
        ];
    }
}
