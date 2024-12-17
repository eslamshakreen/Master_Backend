<?php
namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        // 'registration_date', // Laravel يضيف created_at تلقائيًا
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // العلاقات
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function completedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_user', 'user_id', 'lesson_id')->withTimestamps();
    }

    // الحلقات المكتملة
    public function completedEpisodes()
    {
        return $this->belongsToMany(Episode::class, 'episode_user', 'user_id', 'episode_id')->withTimestamps();
    }

    // التحقق من التسجيل في دورة
    public function isEnrolledInCourse($courseId)
    {
        return $this->enrolledCourses()->where('course_id', $courseId)->exists();
    }


    // الدورات المسجل فيها
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'student_id', 'course_id')->withTimestamps();
    }


}

