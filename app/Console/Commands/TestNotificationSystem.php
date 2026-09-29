<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Notifications\OrderStatusChanged;
use App\Models\Pesanan;

class TestNotificationSystem extends Command
{
    protected $signature = 'test:notifications {user_id?}';
    protected $description = 'Test the notification system';

    public function handle()
    {
        $userId = $this->argument('user_id') ?? 1;
        $user = User::find($userId);

        if (!$user) {
            $this->error("User with ID {$userId} not found");
            return 1;
        }

        $this->info("Testing notification system for user: {$user->username}");

        // Check if user has Notifiable trait
        $traits = class_uses_recursive(get_class($user));
        if (in_array('Illuminate\\Notifications\\Notifiable', $traits)) {
            $this->info('✓ User model has Notifiable trait');
        } else {
            $this->error('✗ User model missing Notifiable trait');
            return 1;
        }

        // Test notification methods availability
        $methods = [
            'notifications' => method_exists($user, 'notifications'),
            'unreadNotifications' => method_exists($user, 'unreadNotifications'),
            'readNotifications' => method_exists($user, 'readNotifications'),
        ];

        foreach ($methods as $method => $exists) {
            if ($exists) {
                $this->info("✓ Method {$method}() is available");
            } else {
                $this->error("✗ Method {$method}() is not available");
            }
        }

        // Test database connection and counts
        try {
            $notificationCount = $user->notifications()->count();
            $unreadCount = $user->unreadNotifications()->count();
            $readCount = $user->readNotifications()->count();

            $this->info("Database connection successful:");
            $this->line("  - Total notifications: {$notificationCount}");
            $this->line("  - Unread notifications: {$unreadCount}");
            $this->line("  - Read notifications: {$readCount}");

            return 0;
        } catch (\Exception $e) {
            $this->error("Database error: " . $e->getMessage());
            return 1;
        }
    }
}
