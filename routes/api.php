<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\LessonController;
use App\Http\Controllers\API\HeroController;
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

Route::prefix('courses')->group(function () {
    Route::get('/', [CourseController::class, 'index']);
    Route::get('/{id}', [CourseController::class, 'show']);

});
Route::get('/info', [HeroController::class, 'index']);

Route::prefix('student')->group(function () {
    Route::post('/register', [AuthController::class, 'registerStudent']);
    Route::post('/login', [AuthController::class, 'loginStudent']);


    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'studentProfile']);
        Route::post('/update-profile', [AuthController::class, 'updateStudentProfile']);
        Route::post('/update-password', [AuthController::class, 'updatePassword']);
        Route::post('/logout', [AuthController::class, 'logoutStudent']);
        Route::prefix('episodes')->group(function () {
            Route::get('/{id}', [LessonController::class, 'show']);
            Route::post('/{id}/mark-completed', [LessonController::class, 'markCompleted']);
            Route::post('/{id}/mark-completed', [LessonController::class, 'markEpisodeCompleted']);
        });

        Route::prefix('courses')->group(function () {
            Route::post('/{id}/enroll', [CourseController::class, 'enroll']);

        });


    });
});
