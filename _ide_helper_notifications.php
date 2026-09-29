<?php

/**
 * IDE Helper for Laravel Notification System
 * This file helps IDEs understand the dynamic methods from the Notifiable trait.
 * Add this to your .gitignore if you don't want to commit it.
 */

namespace App\Models {

    use Illuminate\Notifications\DatabaseNotificationCollection;
    use Illuminate\Notifications\DatabaseNotification;

    /**
     * @property-read DatabaseNotificationCollection|DatabaseNotification[] $notifications
     * @property-read DatabaseNotificationCollection|DatabaseNotification[] $unreadNotifications
     * @property-read DatabaseNotificationCollection|DatabaseNotification[] $readNotifications
     * @method DatabaseNotificationCollection notifications()
     * @method DatabaseNotificationCollection unreadNotifications()
     * @method DatabaseNotificationCollection readNotifications()
     * @method void notify($notification)
     * @method void notifyNow($notification)
     */
    class User {}
}
