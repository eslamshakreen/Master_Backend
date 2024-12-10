<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hero;

use App\Http\Resources\HeroResource;

class HeroController extends Controller
{
    public function index()
    {
        $heroes = Hero::orderBy('order')->get();
        return response()->api(HeroResource::collection($heroes)->additional(['count' => $heroes->count()]), 0, 'تم الحصول على الصور بنجاح', 200);
    }
}
