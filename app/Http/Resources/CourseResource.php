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
            'original_price' => $this->original_price,
            'discounted_price' => $this->discounted_price,
            'thumbnail' => $this->thumbnail ? url('storage/' . $this->thumbnail) : null,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'number_of_episodes' => $this->number_of_episodes,
            'lessons' => LessonResource::collection($this->whenLoaded('lessons')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
