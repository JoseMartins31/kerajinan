<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Update users with empty emails
$count = DB::table('users')
    ->whereNull('email')
    ->orWhere('email', '')
    ->count();

echo "Found {$count} users without email addresses.\n";

if ($count > 0) {
    // Update each user with a unique placeholder email
    DB::table('users')
        ->whereNull('email')
        ->orWhere('email', '')
        ->get()
        ->each(function ($user) {
            $newEmail = "user_{$user->id}_" . time() . "@placeholder.local";
            DB::table('users')
                ->where('id', $user->id)
                ->update(['email' => $newEmail]);
            echo "Updated user ID {$user->id} with email: {$newEmail}\n";
        });

    echo "All users have been updated with placeholder emails.\n";
} else {
    echo "All users already have email addresses.\n";
}
