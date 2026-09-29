<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller for handling user notifications
 * Requires User model to use Illuminate\Notifications\Notifiable trait
 */
class NotificationController extends Controller
{
    /**
     * Display all notifications for the authenticated user.
     */
    public function index()
    {
        $user = Auth::user();

        // Ensure user has Notifiable trait
        if (!in_array('Illuminate\\Notifications\\Notifiable', class_uses_recursive(get_class($user)))) {
            abort(500, 'User model must use Notifiable trait');
        }

        $notifications = $user->notifications()->paginate(20);

        return view('user.notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications count (for AJAX requests).
     */
    public function getUnreadCount()
    {
        $user = Auth::user();
        $count = $user->unreadNotifications()->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Get recent notifications (for dropdown/sidebar).
     */
    public function getRecent()
    {
        $user = Auth::user();
        $notifications = $user
            ->notifications()
            ->take(10)
            ->get();

        return response()->json(['notifications' => $notifications]);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = $user
            ->notifications()
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Delete a specific notification.
     */
    public function delete($id)
    {
        $user = Auth::user();
        $notification = $user
            ->notifications()
            ->where('id', $id)
            ->first();

        if ($notification) {
            $notification->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    /**
     * Clear all read notifications.
     */
    public function clearRead()
    {
        $user = Auth::user();
        $user->readNotifications()->delete();

        return response()->json(['success' => true]);
    }

    /**
     * Admin notifications index page (placeholder for future development).
     */
    public function adminIndex()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }
}
