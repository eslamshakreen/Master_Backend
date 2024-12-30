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

        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|string|max:255|unique:users',
                'password' => 'required|string|min:6',
                'phone' => 'required|string|max:255|unique:users',
                'age' => 'required|string|max:255',
                'gender' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'country' => 'required|string|max:255',
                'degree' => 'string|max:255',
                'company' => 'string|max:255',
                'job_title' => 'string|max:255',



                // 'password_confirmation' => 'required|same:password',
            ]);
        } catch (ValidationException $e) {
            return response()->api([], 1, $e->validator->errors(), 422);
        }

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
        $user = $request->user();

        if (!$user) {
            return response()->api([], 1, ['message' => 'يجب تسجيل الدخول'], 401);
        }

        // Use try-catch to handle validation errors
        try {
            $validatedData = $request->validate([
                'name' => 'string|max:255',
                'email' => 'email|string|max:255',
                'phone' => 'string|max:255',
                'phone_2' => 'string|max:255',
                'country' => 'string|max:255',
                'degree' => 'string|max:255',
                'company' => 'string|max:255',
                'job_title' => 'string|max:255',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image file
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->api([], 1, $e->errors(), 422);
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('students', 'public');
            $validatedData['image'] = $imagePath;
        }

        $user->update(array_filter($validatedData));

        return response()->api($user, 0, 'تم تحديث الملف الشخصي بنجاح');
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
