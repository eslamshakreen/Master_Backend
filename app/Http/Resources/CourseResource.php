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
            'trial_video' => $this->trial_video,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'testimonials' => TestimonialResource::collection(resource: $this->whenLoaded('testimonials')),

            'number_of_episodes' => $this->number_of_episodes,
            'lessons' => LessonResource::collection($this->whenLoaded('lessons')),

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function toArrayWithoutVideoUrl($request)
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
            'trial_video' => $this->trial_video,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'teacher' => new TeacherResource($this->whenLoaded('teacher')),
            'testimonials' => TestimonialResource::collection(resource: $this->whenLoaded('testimonials')),
            'number_of_episodes' => $this->number_of_episodes,
            'lessons' => LessonResource::collection($this->whenLoaded('lessons'))->map(function ($lesson) {
                return collect($lesson)->except(['video_url', 'episodes'])->merge([
                    'episodes' => EpisodeResource::collection($lesson->episodes)->map(function ($episode) {
                        return collect($episode)->except(['video_url']);
                    }),
                ]);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
