<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    // تحويل الدورة إلى مصفوفة
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price_usd' => $this->price_usd,
            'price_lyd' => $this->price_lyd,
            'discounted_price_usd' => $this->discounted_price_usd,
            'discounted_price_lyd' => $this->discounted_price_lyd,
            'thumbnail' => $this->thumbnail ? 'storage/' . $this->thumbnail : null,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'number_of_episodes' => $this->number_of_episodes,
            'lessons' => LessonResource::collection($this->whenLoaded('lessons')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
