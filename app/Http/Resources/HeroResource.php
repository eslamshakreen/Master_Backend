<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeroResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            $this->type => [
                'id' => $this->id,
                'name' => $this->name,
                'title' => $this->title,
                'description' => $this->description,
                'image_url' => $this->image ? 'storage/' . $this->image : null,
                'order' => $this->order,
            ],
        ];
    }
}
