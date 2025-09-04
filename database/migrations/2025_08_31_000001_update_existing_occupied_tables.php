<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Table;
use App\Models\Order;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing tables that have active orders (kot status) to set occupied_at timestamp
        $tablesWithActiveOrders = Table::whereHas('activeOrder')->get();
        
        foreach ($tablesWithActiveOrders as $table) {
            $oldestActiveOrder = Order::where('table_id', $table->id)
                ->where('status', 'kot')
                ->orderBy('created_at', 'asc')
                ->first();
                
            if ($oldestActiveOrder) {
                $table->update([
                    'available_status' => 'running',
                    'occupied_at' => $oldestActiveOrder->created_at,
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset all occupied_at timestamps
        Table::where('occupied_at', '!=', null)->update(['occupied_at' => null]);
    }
};