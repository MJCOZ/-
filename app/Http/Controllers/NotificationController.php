<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * قائمة إشعارات المستخدم (وتعليمها كمقروءة).
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(20);

        // تعليم غير المقروءة كمقروءة عند الفتح
        $user->unreadNotifications->markAsRead();

        return view('notifications.index', compact('notifications'));
    }

    /**
     * تعليم كل الإشعارات كمقروءة.
     */
    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return back()->with('status', 'تم تعليم كل الإشعارات كمقروءة.');
    }
}
