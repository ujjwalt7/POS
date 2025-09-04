<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class KillLongDBConnections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:kill-long-connections';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kill MySQL connections that have been running too long';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $maxTime = 60; // seconds threshold (adjust as needed)

        $connections = DB::select("SHOW PROCESSLIST");

        foreach ($connections as $connection) {
            if (
                $connection->Command === 'Query' &&
                $connection->Time > $maxTime
            ) {
                DB::statement("KILL {$connection->Id}");
                $this->info("Killed connection ID: {$connection->Id} (Running for {$connection->Time}s)");
            }
        }

        return Command::SUCCESS;
    }
}
