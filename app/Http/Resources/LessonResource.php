<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\EpisodeResource;


class LessonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'video_url' => $this->video_url,
            'course_id' => $this->course_id,
            'lesson_order' => $this->lesson_order,
            'episodes' => EpisodeResource::collection($this->whenLoaded('episodes')),
        ];
    }
}
