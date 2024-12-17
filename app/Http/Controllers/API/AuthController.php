<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // تسجيل طالب جديد
    public function registerStudent(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255|unique:users',
            'password' => 'required|string|min:6',
            // 'password_confirmation' => 'required|same:password',
        ]);

        // dd($validatedData);
        // إنشاء مستخدم جديد بدور 'student'
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => 'student', // تأكد من أن لديك حقل 'role' في جدول 'users'
        ]);


        Student::create([
            'user_id' => $user->id,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [

            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ];

        return response()->api($data, 0, 'تم التسجيل بنجاح');
    }

    public function updateProfile(Request $request)
    {
        if (!$request->user()) {
            return response()->api([], 1, ['message' => 'يجب تسجيل الدخول'], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|string|max:255',
        ]);

        $user = User::find(auth()->user()->id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        return response()->api($user, 0, 'تم التحديث بنجاح');
    }

    public function updatePassword(Request $request)
    {
        if (!$request->user()) {
            return response()->api([], 1, ['message' => 'يجب تسجيل الدخول'], 401);
        }

        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::find(auth()->user()->id);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return response()->api($user, 0, 'تم التحديث بنجاح');
    }
    // تسجيل دخول الطالب
    public function loginStudent(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        // التحقق من صحة بيانات الاعتماد وأن المستخدم طالب
        if (!$user || !Hash::check($request->password, $user->password) || $user->role !== 'student') {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);

        }

        $token = $user->createToken('auth_token')->plainTextToken;
        $data = [
            'message' => 'تم تسجيل الدخول بنجاح',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ];

        return response()->api($data, 0, 'تم تسجيل الدخول بنجاح');
    }

    // جلب بيانات ملف الطالب
    public function studentProfile(Request $request)
    {
        $user = $request->user();

        // تحميل بيانات الطالب المرتبطة
        $student = Student::where('user_id', $user->id)->first();

        return response()->json([
            'user' => $user,
            'student' => $student,
        ]);
    }

    // تسجيل خروج الطالب
    public function logoutStudent(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح',
        ]);
    }
}
