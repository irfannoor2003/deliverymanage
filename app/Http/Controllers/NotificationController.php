<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function unreadCount()
    {
        return response()->json([
            'count' => Auth::user()->unreadNotifications->count()
        ]);
    }

    public function index(Request $request)
    {
        $limit = $request->limit ?? 20;
        $notifications = Auth::user()->notifications()->latest()->take($limit)->get();
        return response()->json($notifications);
    }

    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    }
}
