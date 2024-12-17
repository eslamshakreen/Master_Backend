<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function enroll(Request $request, $course_id)
    {
        $user = auth()->user();
        $course = Course::find($course_id);

        if (!$course) {
            return response()->json(['message' => 'الدورة غير موجودة'], 404);
        }


        $existing = Enrollment::where('student_id', $user->id)->where('course_id', $course->id)->first();
        if ($existing) {
            return response()->json(['message' => 'أنت مشترك بالفعل في هذه الدورة أو طلبك قيد الانتظار'], 200);
        }

        $enrollment = Enrollment::create([
            'student_id' => $user->id,
            'course_id' => $course->id,
            'enrollment_date' => now(),
            'completion_status' => false,
            'status' => 'pending'

        ]);

        return response()->json(['message' => 'تم إنشاء طلب الاشتراك، يرجى دفع القيمة للمدير وانتظار التفعيل'], 201);
    }
}
