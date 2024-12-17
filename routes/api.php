<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\LessonController;
use App\Http\Controllers\API\HeroController;
use App\Http\Controllers\API\EpisodeController;
use App\Http\Controllers\API\EnrollmentController;

use App\Http\Controllers\API\LeadController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/leads', [LeadController::class, 'store']);

Route::prefix('courses')->group(function () {
    Route::get('/', [CourseController::class, 'index']);
    Route::get('/{id}', [CourseController::class, 'show']);

});
Route::get('/info', [HeroController::class, 'index']);
Route::get('/footer', [HeroController::class, 'showFooter']);

Route::prefix('student')->group(function () {
    Route::post('/register', [AuthController::class, 'registerStudent']);
    Route::post('/login', [AuthController::class, 'loginStudent']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'studentProfile']);
        Route::post('/update-profile', [AuthController::class, 'updateStudentProfile']);
        Route::post('/update-password', [AuthController::class, 'updatePassword']);
        Route::post('/logout', [AuthController::class, 'logoutStudent']);


        Route::prefix('lessons')->group(function () {
            Route::get('/{id}', [LessonController::class, 'show']);
            Route::get('/courses/{id}', [LessonController::class, 'getLessonsByCourse']);
            Route::post('/{id}/mark-completed', [LessonController::class, 'markLessonCompleted']);
        });



        Route::prefix('episodes')->group(function () {
            Route::get('/{id}', [EpisodeController::class, 'show']);
            Route::get('/lessons/{id}', [EpisodeController::class, 'getEpisodesByLesson']);
            Route::post('/{id}/mark-completed', [EpisodeController::class, 'markEpisodeCompleted']);
        });

        Route::prefix('courses')->group(function () {
            Route::get('/enrolled', [CourseController::class, 'getEnrolledCourses']);
            Route::post('/{course_id}/enroll', [EnrollmentController::class, 'enroll']);


        });


    });
});
