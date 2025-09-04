<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Table;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        $this->updateTableStatus($order);
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        $this->updateTableStatus($order);
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        if ($order->table_id) {
            $this->checkAndUpdateTableAvailability($order->table_id);
        }
    }

    /**
     * Update table status based on order status
     */
    private function updateTableStatus(Order $order): void
    {
        if (!$order->table_id) {
            return;
        }

        $table = Table::find($order->table_id);
        if (!$table) {
            return;
        }

        // Start timer when order is created (kot status)
        if ($order->status === 'kot') {
            if ($table->available_status !== 'running') {
                $table->markAsOccupied();
            }
        }
        // End timer when order is billed
        elseif ($order->status === 'billed') {
            if ($table->available_status === 'running') {
                $table->markAsAvailable();
            }
        }
        // For other statuses (paid, cancelled, etc.), check if there are any active orders
        else {
            $this->checkAndUpdateTableAvailability($order->table_id);
        }
    }

    /**
     * Check if table should be available based on active orders
     */
    private function checkAndUpdateTableAvailability(int $tableId): void
    {
        $table = Table::find($tableId);
        if (!$table) {
            return;
        }

        // Check if there are any orders in 'kot' status (active orders)
        $hasActiveOrders = Order::where('table_id', $tableId)
            ->where('status', 'kot')
            ->exists();

        if (!$hasActiveOrders && $table->available_status === 'running') {
            $table->markAsAvailable();
        }
    }
}