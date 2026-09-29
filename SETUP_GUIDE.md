# Database Notification System - Setup Guide

## Step-by-Step Implementation Guide

### 1. **Database Setup**

```bash
# Run the notifications table migration
php artisan migrate
```

### 2. **Include Notification Bell in Layout**

**Option A: If you have a main layout file (e.g., `resources/views/layouts/app.blade.php`)**
Add this in your navigation section:

```blade
@auth
    @include('components.notification-dropdown')
@endauth
```

**Option B: If you need to add Alpine.js and Tailwind CSS**
Add these to your layout's `<head>` section:

```blade
<!-- Alpine.js for interactive components -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Tailwind CSS for styling -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- CSRF Token for AJAX requests -->
<meta name="csrf-token" content="{{ csrf_token() }}">
```

### 3. **Update User Routes (if needed)**

Ensure these routes are added to your authenticated user section in `routes/web.php`:

```php
// These are already added to your routes file, but verify they exist:
Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::get('/notifications/count', [NotificationController::class, 'getUnreadCount'])->name('notifications.count');
Route::get('/notifications/recent', [NotificationController::class, 'getRecent'])->name('notifications.recent');
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
Route::delete('/notifications/{id}', [NotificationController::class, 'delete'])->name('notifications.delete');
Route::delete('/notifications/clear-read', [NotificationController::class, 'clearRead'])->name('notifications.clear-read');
```

### 4. **Fix User Order Routes (if needed)**

Add these routes to the user section if they don't exist:

```php
Route::get('/my-orders', [TransaksiController::class, 'myOrders'])->name('user.orders.index');
Route::get('/my-orders/{idPesanan}', [TransaksiController::class, 'orderDetail'])->name('user.orders.show');
```

### 5. **Test the Notification System**

**Step 1: Create a test order**

1. Go to your website as a regular user
2. Add products to cart and complete an order
3. Upload payment proof

**Step 2: Test admin notifications**

1. Login as admin
2. Go to pending payments: `/admin/payments/pending`
3. Confirm or reject a payment
4. Check user notifications

**Step 3: Test order status changes**

1. As admin, go to orders: `/admin/orders`
2. Change order status
3. Check user gets notification

### 6. **Verify Notification Components**

**Check notification bell appears:**

- Login as user
- Look for bell icon in navigation
- Should show unread count if there are notifications

**Check notification page:**

- Visit `/notifications` as logged-in user
- Should show notification management interface

### 7. **Common Integration Points**

**Add to main navigation:**

```blade
<nav class="navbar">
    <!-- Other navigation items -->

    @auth
        <div class="flex items-center space-x-4">
            <!-- User menu -->
            <div class="relative">
                <span>{{ Auth::user()->name }}</span>
            </div>

            <!-- Notification Bell -->
            @include('components.notification-dropdown')
        </div>
    @endauth
</nav>
```

**Add to user dashboard:**

```blade
<div class="dashboard">
    <h1>Dashboard</h1>

    <!-- Quick notification summary -->
    <div class="notification-summary">
        <a href="{{ route('notifications.index') }}" class="btn">
            View Notifications
            <span class="badge" id="notification-count"></span>
        </a>
    </div>
</div>

<script>
// Load notification count on dashboard
fetch('/notifications/count')
    .then(response => response.json())
    .then(data => {
        const badge = document.getElementById('notification-count');
        if (data.count > 0) {
            badge.textContent = data.count;
            badge.style.display = 'inline';
        }
    });
</script>
```

### 8. **Troubleshooting**

**If notifications don't appear:**

1. Check database connection
2. Verify migrations ran: `php artisan migrate:status`
3. Check user has `Notifiable` trait (already included)
4. Verify routes are registered: `php artisan route:list | grep notifications`

**If AJAX requests fail:**

1. Check CSRF token is included in layout
2. Verify JavaScript console for errors
3. Check network tab for failed requests

**If styling looks broken:**

1. Ensure Tailwind CSS is loaded
2. Check Alpine.js is loaded for interactive features
3. Verify CSS classes are available

### 9. **Testing Commands**

```bash
# Check routes
php artisan route:list | grep notifications

# Check migrations
php artisan migrate:status

# Clear cache if needed
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Generate some test data (if needed)
php artisan tinker
# In tinker:
# $user = User::first();
# $pesanan = $user->pesanan()->first();
# $user->notify(new App\Notifications\OrderStatusChanged($pesanan, 'old_status', 'new_status'));
```

### 10. **Customization Options**

**Change notification polling frequency:**
In `notification-dropdown.blade.php`, modify:

```javascript
// Change from 30000ms (30 seconds) to desired interval
setInterval(() => loadUnreadCount(), 10000); // 10 seconds
```

**Add email notifications:**
In notification classes, change:

```php
public function via(object $notifiable): array
{
    return ['database', 'mail']; // Add 'mail' channel
}
```

**Customize notification messages:**
Edit the messages in the notification classes' `toArray()` method.

### 11. **Production Considerations**

**Database Cleanup:**
Consider adding a scheduled job to clean old notifications:

```bash
php artisan make:command CleanOldNotifications
```

**Performance:**

- Index the notifications table by user_id and read_at
- Consider pagination for users with many notifications

**Caching:**

- Cache unread counts for high-traffic sites
- Use Redis for real-time features if needed

## Quick Start Checklist

- [ ] Run `php artisan migrate`
- [ ] Add notification bell to layout
- [ ] Include required CSS/JS dependencies
- [ ] Test with a sample order status change
- [ ] Verify notifications appear for users
- [ ] Check notification management page works

Your notification system is now ready to use!
