<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'commission_percentage' => $this->commission_percentage,
            'image' => $this->user->image ? 'storage/' . $this->user->image : null,
            'bio' => $this->bio,
            'job_title' => $this->job_title,

        ];
    }
}
