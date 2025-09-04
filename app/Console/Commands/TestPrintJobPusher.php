<?php

namespace App\Console\Commands;

use App\Events\PrintJobCreated;
use App\Models\PrintJob;
use Illuminate\Console\Command;

class TestPrintJobPusher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:print-job-pusher {print_job_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Pusher print job broadcasting';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $printJobId = $this->argument('print_job_id');

        if ($printJobId) {
            $printJob = PrintJob::find($printJobId);
            if (!$printJob) {
                $this->error("Print job with ID {$printJobId} not found.");
                return 1;
            }
        } else {
            $printJob = PrintJob::latest()->first();
            if (!$printJob) {
                $this->error("No print jobs found in the database.");
                return 1;
            }
        }

        $this->info("Testing Pusher broadcasting for Print Job #{$printJob->id}");

        try {
            event(new PrintJobCreated($printJob));
            $this->info('PrintJobCreated event dispatched successfully!');
            $this->info('Check your browser console for real-time print job notifications.');
        } catch (\Exception $e) {
            $this->error('Failed to dispatch event: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
