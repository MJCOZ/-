<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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

    /**
     * نموذج تعديل الملف الشخصي.
     */
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    /**
     * تحديث الاسم والبريد.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ], [], [
            'name' => 'الاسم',
            'email' => 'البريد الإلكتروني',
        ]);

        $user->update($data);

        return back()->with('status', 'تم تحديث بياناتك بنجاح.');
    }

    /**
     * تغيير كلمة المرور.
     */
    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(6)],
        ], [
            'current_password.current_password' => 'كلمة المرور الحالية غير صحيحة.',
        ], [
            'current_password' => 'كلمة المرور الحالية',
            'password' => 'كلمة المرور الجديدة',
        ]);

        Auth::user()->update(['password' => Hash::make($data['password'])]);

        return back()->with('status', 'تم تغيير كلمة المرور بنجاح.');
    }
}
