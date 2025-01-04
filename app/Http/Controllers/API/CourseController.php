<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Models\Lesson;
use \App\Http\Resources\LessonResource;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $courses = Course::with(['category', 'teacher.user'])
            ->paginate(10);

        $ip = $request->ip();
        $location = geoip($ip);
        $country = $location->getAttribute('country');

        $coursesData = $courses->map(function ($course) use ($country) {
            if ($country === 'Libya') {
                $price = $course->price_lyd . ' LYD';
                $discount = $course->discounted_price_lyd ? $course->discounted_price_lyd . ' LYD' : null;
            } else {
                $price = $course->price_usd . ' USD';
                $discount = $course->discounted_price_usd ? $course->discounted_price_usd . ' USD' : null;
            }

            return [
                'course' => new CourseResource($course),
                'original_price' => $price,
                'discount_price' => $discount
            ];
        });

        return response()->api($coursesData, 0, 'تم الحصول على الدورات بنجاح');
    }

    public function show(Request $request, $id)
    {
        $course = Course::with(['category', 'teacher.user', 'lessons.episodes', 'testimonials'])->find($id);
        if (!$course) {
            return response()->json(['message' => 'الدورة غير موجودة'], 404);
        }

        $ip = $request->ip();
        $location = geoip($ip);
        $country = $location->getAttribute('country');

        if ($country === 'Libya') {
            $price = $course->price_lyd . ' LYD';
            $discount = $course->discounted_price_lyd ? $course->discounted_price_lyd . ' LYD' : null;
        } else {
            $price = $course->price_usd . ' USD';
            $discount = $course->discounted_price_usd ? $course->discounted_price_usd . ' USD' : null;
        }

        $courseData = [
            'course' => (new CourseResource($course))->toArrayWithoutVideoUrl($request),
            'original_price' => $price,
            'discount_price' => $discount,
        ];

        return response()->api($courseData, 0, 'تم الحصول على الدورات بنجاح');
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

    public function getEnrolledCourses(Request $request)
    {
        $user = $request->user();
        $enrolledCourses = $user->enrolledCourses()->with(['category', 'teacher.user', 'enrollments'])->get();

        if ($enrolledCourses->isEmpty()) {
            return response()->json(['message' => 'لا يوجد دورات مسجلة'], 404);
        }

        $ip = $request->ip();
        $location = geoip($ip);
        $country = $location->getAttribute('country');

        $coursesData = $enrolledCourses->map(function ($course) use ($country) {
            if ($country === 'Libya') {
                $price = $course->price_lyd . ' LYD';
                $discount = $course->discount_price_lyd ? $course->discount_price_lyd . ' LYD' : null;
            } else {
                $price = $course->price_usd . ' USD';
                $discount = $course->discount_price_usd ? $course->discount_price_usd . ' USD' : null;
            }

            return [
                'course' => new CourseResource($course),
                'original_price' => $price,
                'discount_price' => $discount
            ];
        });

        return response()->api($coursesData, 0, 'تم الحصول على الدورات المسجلة بنجاح');
    }



}
