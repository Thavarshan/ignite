<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

#[Signature('notifications:prune')]
#[Description('Delete read notifications older than 30 days and unread notifications older than 90 days')]
class PruneOldNotifications extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $readDeleted = DB::table('notifications')
            ->whereNotNull('read_at')
            ->where('created_at', '<', now()->subDays(30))
            ->delete();

        $unreadDeleted = DB::table('notifications')
            ->whereNull('read_at')
            ->where('created_at', '<', now()->subDays(90))
            ->delete();

        $this->info("Pruned {$readDeleted} read and {$unreadDeleted} unread notifications.");

        return self::SUCCESS;
    }
}
