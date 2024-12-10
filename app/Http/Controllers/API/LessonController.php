<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LessonResource;
use App\Models\Lesson;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Episode;

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
            return response()->api([], 1, ['message' => 'the lesson not found'], 404);
        }

        return response()->api(new LessonResource($lesson), 0, 'تم الحصول على الدرس بنجاح');
    }

    // تحديد الدرس كمكتمل
    public function markCompleted(Request $request, $id)
    {
        $user = $request->user();
        $lesson = Lesson::find($id);

        if (!$lesson) {
            return response()->api([], 1, ['message' => 'الدرس غير موجود'], 404);
        }

        // تحقق مما إذا كان الطالب مسجلاً في الدورة
        if (!$user->isEnrolledInCourse($lesson->course_id)) {
            return response()->api([], 1, ['message' => 'أنت غير مسجل في هذه الدورة'], 403);
        }

        // تحديد الدرس كمكتمل للطالب
        $user->completedLessons()->attach($lesson->id);

        return response()->api([], 0, ['message' => 'تم تحديد الدرس كمكتمل'], 200);
    }

    public function markEpisodeCompleted(Request $request, $id)
    {
        if (!$request->user()) {
            return response()->api([], 1, ['message' => 'يجب تسجيل الدخول'], 401);
        }
        $user = $request->user();
        $episode = Episode::find($id);

        if (!$episode) {
            return response()->api([], 1, ['message' => 'الحلقة غير موجودة'], 404);
        }

        // تحقق مما إذا كان الطالب مسجلاً في الدورة
        if (!$user->isEnrolledInCourse($episode->lesson->course_id)) {
            return response()->api([], 1, ['message' => 'أنت غير مسجل في هذه الدورة'], 403);
        }

        // تحديد الحلقة كمكتملة للطالب
        $user->completedEpisodes()->attach($episode->id);

        return response()->api([], 0, ['message' => 'تم تحديد الحلقة كمكتملة'], 200);
    }
}
