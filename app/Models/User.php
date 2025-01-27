<?php
namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, Notifiable, HasFactory;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'image',
        'age',
        'gender',
        'address',
        'country',
        'phone_2',
        'degree',
        'company',
        'job_title',
        'number_of_employees',
        'is_price_visible',
        'is_discount_visible',
        'headline_one',
        'headline_two',
        'headline_three',
        'description_one',
        'description_two',
        'description_three',
        'image_one',
        'image_two',
        'image_three',
        'call_to_action_one',
        'call_to_action_two',
        'call_to_action_three',
        'call_to_action_link_one',
        'call_to_action_link_two',
        'call_to_action_link_three',
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

