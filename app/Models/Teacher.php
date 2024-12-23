<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Course;

use App\Models\User;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'commission_percentage',
        'total_earnings',
        'image',
        'bio',
        'job_title',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
