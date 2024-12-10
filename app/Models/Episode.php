<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'video_url',
        'lesson_id',
        'episode_order',
    ];

    // العلاقات
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function isCompletedByUser($user)
    {
        if (!$user) {
            return false; // Return false or handle the error as needed
        }
        return $user->completedEpisodes()->where('episode_id', $this->id)->exists();
    }

}
