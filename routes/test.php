<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Test Routes (for development only - remove in production)
|--------------------------------------------------------------------------
*/

if (app()->environment('local')) {
    Route::get('/test-notifications', function () {
        $user = User::first();

        if (!$user) {
            return response()->json(['error' => 'No user found'], 404);
        }

        // Test if notification methods are available
        $methods = [];
        $methods['notifications_available'] = method_exists($user, 'notifications');
        $methods['unread_notifications_available'] = method_exists($user, 'unreadNotifications');
        $methods['read_notifications_available'] = method_exists($user, 'readNotifications');

        // Test trait usage
        $traits = class_uses_recursive(get_class($user));
        $methods['notifiable_trait'] = in_array('Illuminate\Notifications\Notifiable', $traits);

        // Try to get notification counts (will fail if DB not connected)
        try {
            $methods['notification_count'] = $user->notifications()->count();
            $methods['unread_count'] = $user->unreadNotifications()->count();
        } catch (\Exception $e) {
            $methods['db_error'] = $e->getMessage();
        }

        return response()->json([
            'message' => 'Notification system test',
            'user' => $user->only(['id', 'username', 'email']),
            'methods' => $methods
        ]);
    })->name('test.notifications');
}
