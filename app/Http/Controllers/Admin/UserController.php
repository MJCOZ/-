<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * قائمة المستخدمين (للمدير فقط).
     */
    public function index()
    {
        $users = User::withCount('reviews')->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * تغيير دور مستخدم.
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(array_keys(User::ROLES))],
        ]);

        // لا يستطيع المدير تغيير دور نفسه (حماية من فقدان الوصول)
        if ($user->id === Auth::id()) {
            return back()->withErrors(['role' => 'لا يمكنك تغيير دورك الخاص.']);
        }

        $user->update(['role' => $data['role']]);

        return back()->with('status', 'تم تحديث دور ' . $user->name . '.');
    }
}
