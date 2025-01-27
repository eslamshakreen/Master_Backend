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
            'enrollment_state' => $this->whenLoaded('enrollments', function () {
                $user = auth()->user();
                $enrollment = $this->enrollments->where('student_id', $user->id)->first();
                return $enrollment ? $enrollment->status : null;
            }),

            'visibility' => [
                'is_price_visible' => $this->is_price_visible,
                'is_discount_visible' => $this->is_discount_visible,
            ],
            'group_one' => [
                'headline' => $this->headline_one,
                'description' => $this->description_one,
                'image' => $this->image_one ? 'storage/' . $this->image_one : null,
                'call_to_action' => $this->call_to_action_one,
                'call_to_action_link' => $this->call_to_action_link_one,
            ],
            'group_two' => [
                'headline' => $this->headline_two,
                'description' => $this->description_two,
                'image' => $this->image_two ? 'storage/' . $this->image_two : null,
                'call_to_action' => $this->call_to_action_two,
                'call_to_action_link' => $this->call_to_action_link_two,
            ],
            'group_three' => [
                'headline' => $this->headline_three,
                'description' => $this->description_three,
                'image' => $this->image_three ? 'storage/' . $this->image_three : null,
                'call_to_action' => $this->call_to_action_three,
                'call_to_action_link' => $this->call_to_action_link_three,
            ],

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
            'visibility' => [
                'is_price_visible' => $this->is_price_visible,
                'is_discount_visible' => $this->is_discount_visible,
            ],
            'group_one' => [
                'headline' => $this->headline_one,
                'description' => $this->description_one,
                'image' => $this->image_one ? 'storage/' . $this->image_one : null,
                'call_to_action' => $this->call_to_action_one,
                'call_to_action_link' => $this->call_to_action_link_one,
            ],
            'group_two' => [
                'headline' => $this->headline_two,
                'description' => $this->description_two,
                'image' => $this->image_two ? 'storage/' . $this->image_two : null,
                'call_to_action' => $this->call_to_action_two,
                'call_to_action_link' => $this->call_to_action_link_two,
            ],
            'group_three' => [
                'headline' => $this->headline_three,
                'description' => $this->description_three,
                'image' => $this->image_three ? 'storage/' . $this->image_three : null,
                'call_to_action' => $this->call_to_action_three,
                'call_to_action_link' => $this->call_to_action_link_three,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
