<?php

namespace App\Console\Commands;

use App\Models\Table;
use App\Models\Order;
use Illuminate\Console\Command;

class UpdateTableTimers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tables:update-timers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update table timers for existing occupied tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating table timers...');

        // Update existing tables that have active orders (kot status) to set occupied_at timestamp
        $tablesWithActiveOrders = Table::whereHas('activeOrder')->get();
        
        $updated = 0;
        foreach ($tablesWithActiveOrders as $table) {
            $oldestActiveOrder = Order::where('table_id', $table->id)
                ->where('status', 'kot')
                ->orderBy('created_at', 'asc')
                ->first();
                
            if ($oldestActiveOrder && !$table->occupied_at) {
                $table->update([
                    'available_status' => 'running',
                    'occupied_at' => $oldestActiveOrder->created_at,
                ]);
                $updated++;
                $this->line("Updated table {$table->table_code} - occupied since {$oldestActiveOrder->created_at}");
            }
        }

        $this->info("Updated {$updated} tables with timer information.");
        
        return 0;
    }
}