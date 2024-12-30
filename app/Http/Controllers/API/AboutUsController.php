<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function index()
    {
        // إذا كان لديك سجل واحد فقط:
        $about = AboutUs::first();
        if (!$about) {
            return response()->json(['message' => 'لا توجد معلومات متاحة'], 404);
        }

        // أو إذا كان لديك عدة سجلات وتريد إرجاعها كلها:
        // $about = AboutUs::all();

        return response()->api(
            [
                'title' => $about->title,
                'description' => $about->description,
                'image' => $about->image ? 'storage/' . $about->image : null,
            ],
            0,
            "تم الحصول على البيانات بنجاح"
        );
    }
}
