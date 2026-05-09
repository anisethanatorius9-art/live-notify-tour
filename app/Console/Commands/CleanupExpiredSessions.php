<?php

namespace App\Console\Commands;

use App\Services\SessionService;
use Illuminate\Console\Command;

class CleanupExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired sessions from the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        SessionService::cleanupExpiredSessions();

        $this->info('Expired sessions have been cleaned up successfully.');

        return self::SUCCESS;
    }
}
