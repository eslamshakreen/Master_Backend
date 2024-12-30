<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Installment;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'course_id',
        'enrollment_id',
        'payment_method_id',
        'paid_amount',
        'total_amount',
        'payment_date',
        'coupon_id',
    ];

    // العلاقات

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function getStudentIdAttribute()
    {
        return $this->enrollment->student_id ?? null;
    }

    public function getCourseIdAttribute()
    {
        return $this->enrollment->course_id ?? null;
    }

    public function getRemainingAmountAttribute()
    {
        return $this->total_amount - $this->paid_amount;
    }

    public function getTeacherCommissionAttribute()
    {
        if (!$this->course || !$this->course->teacher)
            return 0;
        $commission = $this->course->teacher->commission_percentage ?? 0;
        return $this->paid_amount * ($commission / 100);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    protected static function booted()
    {
        static::creating(function ($payment) {
            if ($payment->enrollment && !$payment->course_id) {
                $payment->course_id = $payment->enrollment->course_id;
            }
        });
    }

}
