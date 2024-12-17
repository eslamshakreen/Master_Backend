<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewLeadMail;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:leads,email',
            'phone' => 'nullable|string|max:255'
        ]);

        // إنشاء Lead
        $lead = Lead::create($data);

        // إرسال البريد الإلكتروني مع ملف PDF مرفق
        Mail::to($lead->email)->send(new NewLeadMail($lead));

        return response()->json(['message' => 'تم استلام بياناتك، وتم إرسال المحاضرة المجانية إلى بريدك الإلكتروني'], 201);
    }
}
