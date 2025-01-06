<?php

namespace App\Http\Resources;

use App;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Episode;

class EpisodeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'video_url' => $this->video_url,
            'pdf' => $this->pdf ? 'storage/' . $this->pdf : null,
            'episode_order' => $this->episode_order,
            'is_completed' => $this->isCompletedByUser($request->user()),
        ];
    }
}
