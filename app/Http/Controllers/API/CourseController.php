<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with(['category', 'teacher.user'])
            ->paginate(10);

        return response()->api(CourseResource::collection($courses), 0, 'تم الحصول على الدورات بنجاح');
    }

    public function show($id)
    {
        $course = Course::with([
            'category',
            'teacher.user',
            'lessons.episodes',
        ])->find($id);

        if (!$course) {
            return response()->json(['message' => 'الدورة غير موجودة'], 404);
        }

        return response()->api(new CourseResource($course), 0, 'تم الحصول على الدورة بنجاح');
    }



    public function enroll(Request $request, $id)
    {
        $user = $request->user();
        $course = Course::find($id);

        if (!$course) {
            return response()->json(['message' => 'الدورة غير موجودة'], 404);
        }

        // تحقق مما إذا كان الطالب مسجلاً بالفعل
        if ($user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return response()->json(['message' => 'أنت مسجل بالفعل في هذه الدورة'], 200);
        }

        // إذا كانت الدورة مدفوعة، تحقق من الدفع
        if ($course->original_price > 0) {
            // هنا يمكنك إضافة منطق التعامل مع الدفع
            // على سبيل المثال، التحقق من أن الدفع تم بنجاح قبل تسجيل الطالب
            return response()->json(['message' => 'هذه دورة مدفوعة. يرجى إتمام عملية الدفع.'], 402);
        }

        // تسجيل الطالب في الدورة
        $user->enrolledCourses()->attach($course->id);

        return response()->json(['message' => 'تم تسجيلك في الدورة بنجاح'], 200);
    }
}
