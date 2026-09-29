<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CleanDuplicateUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:clean-duplicates {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up duplicate usernames by keeping the oldest record';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 DRY RUN - No records will be deleted');
        } else {
            if (!$this->confirm('⚠️  This will permanently delete duplicate user records. Are you sure?')) {
                $this->info('Operation cancelled.');
                return;
            }
        }

        $this->info('Finding duplicate usernames...');

        // Get all duplicate usernames
        $duplicateUsernames = User::select('username')
            ->groupBy('username')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('username');

        if ($duplicateUsernames->isEmpty()) {
            $this->info('✅ No duplicate usernames found.');
            return;
        }

        $totalDeleted = 0;

        foreach ($duplicateUsernames as $username) {
            $this->line("Processing username: '$username'");

            // Get all users with this username, ordered by creation date (oldest first)
            $users = User::where('username', $username)
                ->orderBy('created_at', 'asc')
                ->get();

            // Keep the first (oldest) record, delete the rest
            $keepUser = $users->first();
            $duplicates = $users->slice(1);

            $this->line("  Keeping: ID {$keepUser->id} (created: {$keepUser->created_at})");

            foreach ($duplicates as $duplicate) {
                if ($dryRun) {
                    $this->line("  Would delete: ID {$duplicate->id} (created: {$duplicate->created_at})");
                } else {
                    $this->line("  Deleting: ID {$duplicate->id} (created: {$duplicate->created_at})");

                    // Check if this user has any orders before deleting
                    $ordersCount = $duplicate->pesanan()->count();
                    if ($ordersCount > 0) {
                        $this->warn("    ⚠️  User has {$ordersCount} orders - transferring to kept user");
                        // Transfer orders to the kept user
                        $duplicate->pesanan()->update(['user_id' => $keepUser->id]);
                    }

                    $duplicate->delete();
                }
                $totalDeleted++;
            }
        }

        if ($dryRun) {
            $this->info("📊 Would delete {$totalDeleted} duplicate records");
            $this->info("💡 Run without --dry-run to actually delete duplicates");
        } else {
            $this->info("✅ Deleted {$totalDeleted} duplicate records");
            $this->info("🔄 Running verification check...");

            // Verify no duplicates remain
            $remainingDuplicates = User::select('username')
                ->groupBy('username')
                ->havingRaw('COUNT(*) > 1')
                ->count();

            if ($remainingDuplicates > 0) {
                $this->error("❌ Some duplicates still remain!");
            } else {
                $this->info("✅ All duplicates cleaned successfully!");
            }
        }
    }
}
