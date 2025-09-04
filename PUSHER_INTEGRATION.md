# Pusher Integration for Orders

This document explains how Pusher is integrated for real-time order updates in the TableTrack application.

## Overview

The application now supports real-time order updates using Pusher Channels with automatic fallback to polling when Pusher is not available or fails to connect.

## Features

-   **Real-time Updates**: Orders are updated in real-time when created or modified
-   **Automatic Fallback**: Falls back to polling if Pusher is not configured or fails
-   **Visual Indicators**: Shows whether real-time (Pusher) or polling is being used
-   **Configurable**: Can be enabled/disabled through the admin settings

## Configuration

### 1. Pusher Settings

Navigate to **Settings > Push Notifications** and configure:

-   **Enable Live Page Update (Pusher Channels)**: Toggle to enable Pusher broadcasting
-   **Pusher App ID**: Your Pusher app ID
-   **Pusher Key**: Your Pusher key
-   **Pusher Secret**: Your Pusher secret
-   **Pusher Cluster**: Your Pusher cluster (e.g., 'mt1', 'us2', etc.)

### 2. Environment Variables

The following environment variables are automatically set based on your Pusher settings:

-   `BROADCAST_DRIVER`: Set to 'pusher' when enabled, 'null' when disabled
-   `PUSHER_APP_ID`: Your Pusher app ID
-   `PUSHER_APP_KEY`: Your Pusher key
-   `PUSHER_APP_SECRET`: Your Pusher secret
-   `PUSHER_APP_CLUSTER`: Your Pusher cluster

## How It Works

### 1. Event Broadcasting

When an order is created or updated, the `OrderUpdated` event is automatically dispatched:

```php
// In app/Models/Order.php
static::updated(function ($model) {
    event(new OrderUpdated($model, 'updated'));
});

static::created(function ($model) {
    event(new OrderUpdated($model, 'created'));
});
```

### 2. Frontend Integration

The orders page automatically detects if Pusher is enabled and:

1. **If Pusher is enabled**: Connects to Pusher and listens for order updates
2. **If Pusher is disabled or fails**: Falls back to the existing polling mechanism
3. **Visual feedback**: Shows a green "Real-time" indicator when using Pusher

### 3. Desktop Application Integration

Desktop applications can integrate with the print job broadcasting system:

1. **Test Connection**: Call the test connection API to get Pusher configuration
2. **Initialize Pusher**: Set up Pusher client with received configuration
3. **Listen for Jobs**: Subscribe to 'print-jobs' channel for real-time print jobs
4. **Process Jobs**: Print jobs instantly when received via Pusher
5. **Update Status**: Mark jobs as completed or failed via API endpoints

See `DESKTOP_PRINTING_API.md` for detailed integration instructions.

### 3. Channel Structure

Orders are broadcast on the `orders` channel with events:

-   `order.updated`: When an order is modified
-   `order.created`: When a new order is created

## Testing

### Test Command

Use the provided command to test Pusher broadcasting:

```bash
# Test with the latest order
php artisan test:pusher-order

# Test with a specific order ID
php artisan test:pusher-order 123
```

### Manual Testing

1. Open the orders page in your browser
2. Open browser developer tools and check the console
3. Create or update an order from another browser/tab
4. Verify that the orders page updates automatically

## Troubleshooting

### Common Issues

1. **Pusher not connecting**:

    - Check your Pusher credentials in Settings > Push Notifications
    - Verify your Pusher app is active
    - Check browser console for connection errors

2. **Falling back to polling**:

    - This is normal behavior when Pusher is not configured
    - Check the console logs for specific error messages

3. **No real-time updates**:
    - Ensure Pusher is properly configured
    - Check if the broadcasting driver is set to 'pusher'
    - Verify the JavaScript console for any errors

### Debug Information

The application logs debug information to the browser console:

-   Pusher connection status
-   Event reception
-   Fallback to polling when needed

## Files Modified

-   `app/Events/OrderUpdated.php` - New event for order updates
-   `app/Models/Order.php` - Added event dispatching
-   `app/Livewire/Order/Orders.php` - Added Pusher support
-   `resources/views/livewire/order/orders.blade.php` - Updated UI and JavaScript
-   `resources/views/layouts/app.blade.php` - Added Pusher JavaScript library
-   `app/Providers/CustomConfigProvider.php` - Added broadcasting configuration
-   `config/broadcasting.php` - Broadcasting configuration file

## Benefits

-   **Reduced Server Load**: No need for constant polling
-   **Better User Experience**: Instant updates without page refresh
-   **Scalability**: Pusher handles the real-time infrastructure
-   **Reliability**: Automatic fallback ensures functionality even if Pusher fails
