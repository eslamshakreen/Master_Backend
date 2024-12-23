<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Episode;
use App\Http\Resources\EpisodeResource;
use App\Models\Lesson;
use App\Models\Enrollment;

class EpisodeController extends Controller
{

    public function show($id)
    {
        $episode = Episode::with(['lesson'])->find($id);

        if (!$episode) {
            return response()->api([], 1, ['message' => 'الحلقة غير موجودة'], 404);
        }




        return response()->api(new EpisodeResource($episode), 0, 'تم الحصول على الحلقة بنجاح');
    }

    public function getEpisodesByLesson($id)
    {

        $episodes = Episode::with(['lesson'])->where('lesson_id', $id)->get();

        if (!$episodes) {
            return response()->api([], 1, ['message' => 'الحلقات غير موجودة'], 404);
        }

        return response()->api(EpisodeResource::collection($episodes), 0, 'تم الحصول على الحلقات بنجاح');


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

    public function getCompletedEpisodesByCourse(Request $request)
    {
        $user = $request->user();
        $enrollment = Enrollment::where('student_id', auth()->user()->id)
            ->where('status', 'active')
            ->first();

        if (!$enrollment) {
            return response()->json(['message' => 'أنت غير مسجل أو اشتراكك قيد الانتظار'], 403);
        }

        $completedEpisodes = $user->completedEpisodes()->where('course_id', $enrollment->course_id)->get();

        return response()->api(EpisodeResource::collection($completedEpisodes), 0, 'تم الحصول على الحلقات المكتملة بنجاح');
    }


    public function getEpisodesByCourse(Request $request, $courseId)
    {
        $user = $request->user();

        if (!$user) {
            return response()->api([], 1, ['message' => 'يجب تسجيل الدخول'], 401);
        }

        $enrollment = Enrollment::where('student_id', $user->id)
            ->where('course_id', $courseId)
            ->where('status', 'active')
            ->first();



        if (!$enrollment) {
            return response()->json(['message' => 'أنت غير مسجل أو اشتراكك قيد الانتظار'], 403);
        }

        $episodes = Episode::whereHas('lesson', function ($query) use ($courseId) {
            $query->where('course_id', $courseId);
        })->get();



        $episodesData = $episodes->map(function ($episode) {
            return [
                'episode' => new EpisodeResource($episode),
            ];
        });

        return response()->api($episodesData, 0, 'تم الحصول على الحلقات بنجاح');
    }
}
