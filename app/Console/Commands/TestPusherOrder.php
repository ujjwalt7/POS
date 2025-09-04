<?php

namespace App\Console\Commands;

use App\Events\OrderUpdated;
use App\Models\Order;
use Illuminate\Console\Command;

class TestPusherOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:pusher-order {order_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Pusher order broadcasting';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');

        if ($orderId) {
            $order = Order::find($orderId);
            if (!$order) {
                $this->error("Order with ID {$orderId} not found.");
                return 1;
            }
        } else {
            $order = Order::latest()->first();
            if (!$order) {
                $this->error("No orders found in the database.");
                return 1;
            }
        }

        $this->info("Testing Pusher broadcasting for Order #{$order->id}");

        try {
            event(new OrderUpdated($order, 'test'));
            $this->info('OrderUpdated event dispatched successfully!');
            $this->info('Check your browser console for real-time updates.');
        } catch (\Exception $e) {
            $this->error('Failed to dispatch event: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
