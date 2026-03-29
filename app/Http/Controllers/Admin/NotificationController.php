<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        $notifications = Notification::with('order')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $unreadCount = Notification::where('is_read', false)->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Notification::where('is_read', false)->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}