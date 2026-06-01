<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * صفحة الملف الشخصي: معلومات المستخدم ومراجعاته.
     */
    public function show()
    {
        $user = Auth::user();
        $reviews = $user->reviews()->with('title')->latest()->get();

        return view('profile.show', compact('user', 'reviews'));
    }
}
