<?php

namespace App\Console\Commands;

use App\Models\ManagedStudent;
use Illuminate\Console\Command;

class ArchiveOldStudents extends Command
{
    protected $signature = 'students:archive-old';
    protected $description = 'Archive students whose accounts are older than 5 months';

    public function handle()
    {
        $count = ManagedStudent::query()
            ->active()
            ->where('created_at', '<', now()->subMonths(5))
            ->update([
                'status' => 'archived',
                'archived_at' => now(),
            ]);

        $this->info("Archived {$count} student(s).");

        return Command::SUCCESS;
    }
}
