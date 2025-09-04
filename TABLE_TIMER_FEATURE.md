# Table Timer Feature

This feature adds automatic timer tracking for occupied tables in the restaurant POS system.

## What it does

- **Automatic Timer Start**: When a table receives an order (status: 'kot'), the timer automatically starts
- **Real-time Display**: Shows the duration a table has been occupied in HH:MM format
- **Automatic Timer Stop**: When an order is billed (status: 'billed'), the timer stops and resets
- **Multiple View Support**: Timer appears in all table views (list, grid, and layout)

## Implementation Details

### Database Changes
- Added `occupied_at` timestamp column to the `tables` table
- Migration files:
  - `2025_08_31_000000_add_occupied_at_to_tables_table.php`
  - `2025_08_31_000001_update_existing_occupied_tables.php`

### Model Updates
- **Table Model** (`app/Models/Table.php`):
  - Added `occupied_at` to casts
  - Added `markAsOccupied()` method
  - Added `markAsAvailable()` method
  - Added `getOccupiedDurationAttribute()` accessor
  - Added `getFormattedOccupiedDurationAttribute()` accessor

### Observer Pattern
- **OrderObserver** (`app/Observers/OrderObserver.php`):
  - Automatically updates table status when orders are created/updated/deleted
  - Sets `occupied_at` timestamp when order status is 'kot' (table becomes occupied)
  - Clears `occupied_at` when order status is 'billed' (table becomes available)

### UI Components
- **TableTimer Livewire Component** (`app/Livewire/TableTimer.php`):
  - Real-time timer display with auto-refresh every 5 seconds
  - Shows timer only for occupied tables
- **Timer View** (`resources/views/livewire/table-timer.blade.php`):
  - Styled timer with clock icon
  - Monospace font for better readability

### View Integration
- Updated `resources/views/livewire/table/tables.blade.php` to show timers in all views
- Updated `resources/views/components/restaurant-table.blade.php` for layout view support

## Usage

1. **Automatic Operation**: No manual intervention needed - timers start/stop automatically
2. **Visual Indicators**: 
   - Timer appears as a blue badge with clock icon
   - Shows duration in HH:MM format (e.g., "01:23" for 1 hour 23 minutes)
3. **Real-time Updates**: Timer updates every 5 seconds while viewing the tables page

## Commands

- `php artisan tables:update-timers` - Manually update timers for existing occupied tables

## Technical Notes

- Timer uses Laravel's Observer pattern for automatic updates
- Livewire polling ensures real-time display without page refresh
- Graceful handling of edge cases (missing table, no orders, etc.)
- Performance optimized with minimal database queries