<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Episode;
use App\Models\Enrollment;

class LessonController extends Controller
{
    // عرض تفاصيل الدرس والحلقات المرتبطة به
    public function show($id)
    {
        // dd($id);
        if (!auth()->check()) {
            return response()->api([], 1, ['message' => 'يجب تسجيل الدخول'], 401);
        }
        $lesson = Lesson::with(['episodes'])->find($id);
        // dd($lesson);

        if (!$lesson) {
            return response()->api([], 1, ['message' => 'الدرس غير موجود'], 404);
        }

        $enrollment = Enrollment::where('student_id', auth()->user()->id)
            ->where('course_id', $lesson->course_id)
            ->where('status', 'active')
            ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'أنت غير مسجل أو اشتراكك قيد الانتظار'], 403);
        }


        return response()->api(new LessonResource($lesson), 0, 'تم الحصول على الدرس بنجاح');
    }

    public function getLessonsByCourse($id)
    {


        $lessons = Lesson::with(['episodes'])->where('course_id', $id)->get();

        $courseId = $id; // Use the course ID directly from the method parameter
        $enrollment = Enrollment::where('student_id', auth()->user()->id)
            ->where('course_id', $courseId)
            ->where('status', 'active')
            ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'أنت غير مسجل أو اشتراكك قيد الانتظار'], 403);
        }


        return response()->api(LessonResource::collection($lessons), 0, 'تم الحصول على الدروس بنجاح');
    }

    // تحديد الدرس كمكتمل
    public function markLessonCompleted(Request $request, $id)
    {
        $user = $request->user();
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->api([], 1, ['message' => 'الدرس غير موجود'], 404);
        }

        $enrollment = Enrollment::where('student_id', auth()->user()->id)
            ->where('course_id', $lesson->course_id)
            ->where('status', 'active')
            ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'أنت غير مسجل أو اشتراكك قيد الانتظار'], 403);
        }


        // تحقق مما إذا كان الطالب مسجلاً في الدورة
        if (!$user->isEnrolledInCourse($lesson->course_id)) {
            return response()->api([], 1, ['message' => 'أنت غير مسجل في هذه الدورة'], 403);
        }

        // تحديد الدرس كمكتمل للطالب
        $user->completedLessons()->attach($lesson->id);

        return response()->api([], 0, ['message' => 'تم تحديد الدرس كمكتمل'], 200);
    }


}
