<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Episode;
use App\Http\Resources\EpisodeResource;

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
}
