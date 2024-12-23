<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TestimonialResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'job' => $this->job,
            'description' => $this->description,
            'created_at' => $this->created_at,
        ];
    }
}
