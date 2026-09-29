<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CheckDuplicates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:check-duplicates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for duplicate usernames and emails in users table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for duplicate usernames...');

        $duplicateUsernames = User::select('username')
            ->groupBy('username')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('username');

        if ($duplicateUsernames->isEmpty()) {
            $this->info('✅ No duplicate usernames found.');
        } else {
            $this->error('❌ Found duplicate usernames:');
            foreach ($duplicateUsernames as $username) {
                $this->line("Username: '$username'");
                $users = User::where('username', $username)->get();
                foreach ($users as $user) {
                    $this->line("  - ID: {$user->id}, Created: {$user->created_at}");
                }
            }
        }

        $this->info('');
        $this->info('Checking for duplicate emails...');

        $duplicateEmails = User::select('email')
            ->whereNotNull('email')
            ->where('email', '!=', '')
            ->groupBy('email')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('email');

        if ($duplicateEmails->isEmpty()) {
            $this->info('✅ No duplicate emails found.');
        } else {
            $this->error('❌ Found duplicate emails:');
            foreach ($duplicateEmails as $email) {
                $this->line("Email: '$email'");
                $users = User::where('email', $email)->get();
                foreach ($users as $user) {
                    $this->line("  - ID: {$user->id}, Username: {$user->username}, Created: {$user->created_at}");
                }
            }
        }

        // Show total users count
        $totalUsers = User::count();
        $this->info('');
        $this->info("Total users in database: {$totalUsers}");
    }
}
