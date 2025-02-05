<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hero;
use App\Http\Resources\OurClientResource;
use App\Models\OurClient;
use App\Models\FooterLink;
use App\Http\Resources\FooterLinkResource;

use App\Http\Resources\HeroResource;

class HeroController extends Controller
{
    public function index()
    {

        $heroes = Hero::orderBy('order')->get();
        return response()->api(HeroResource::collection($heroes)->additional(['count' => $heroes->count()]), 0, 'تم الحصول على الصور بنجاح', 200);
    }

    public function showFooter()
    {
        $footerLinks = FooterLink::orderBy('order')->get();
        return response()->api(FooterLinkResource::collection($footerLinks)->additional(['count' => $footerLinks->count()]), 0, 'تم الحصول على الصور بنجاح', 200);
    }

    public function clientsSection()
    {
        $ourClients = OurClient::orderBy('order')->get();
        return response()->api(OurClientResource::collection($ourClients)->additional(['count' => $ourClients->count()]), 0, 'تم الحصول على الصور بنجاح', 200);
    }
}
