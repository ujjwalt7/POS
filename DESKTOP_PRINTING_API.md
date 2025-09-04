# Desktop Printing API Integration

This document explains how desktop applications can integrate with the TableTrack print job broadcasting system for live printing.

## Overview

The system broadcasts print jobs in real-time using Pusher Channels, allowing desktop applications to receive print jobs instantly when they are created.

## Integration Steps

### 1. Test Connection

First, test the connection to get Pusher configuration:

```http
GET /api/print-jobs/test-connection?branch={branch_id}
```

**Response:**
```json
{
    "message": "Connection established",
    "status": "success",
    "pusher_enabled": true,
    "pusher_config": {
        "app_id": "126156",
        "key": "a0597c48c6db936c9041",
        "cluster": "mt1",
        "channel": "print-jobs",
        "event": "print-job.created"
    }
}
```

### 2. Initialize Pusher Client

Use the Pusher configuration from the test connection to initialize your Pusher client:

```javascript
// Example using JavaScript/Node.js
const Pusher = require('pusher-js');

const pusher = new Pusher('a0597c48c6db936c9041', {
    cluster: 'mt1',
    encrypted: true
});

const channel = pusher.subscribe('print-jobs');

channel.bind('print-job.created', function(data) {
    console.log('New print job received:', data);
    // Handle the print job here
    handlePrintJob(data);
});
```

### 3. Handle Print Jobs

When a print job is received, it will contain:

```json
{
    "print_job_id": 123,
    "restaurant_id": 1,
    "branch_id": 1,
    "printer_id": 1,
    "status": "pending",
    "payload": {
        "text": "ESC/POS commands for printing",
        "cutPaper": true
    },
    "created_at": "2025-07-07T12:00:00.000000Z",
    "timestamp": "2025-07-07T12:00:00.000000Z",
    "type": "print_job_created",
    "printer_info": {
        "printer_name": "Kitchen Printer",
        "printer_type": "network",
        "print_format": "thermal80mm"
    }
}
```

### 4. Update Print Job Status

After processing a print job, update its status:

```http
POST /api/print-jobs/{print_job_id}/complete
```

**Response:**
```json
{
    "success": true,
    "message": "Print job marked as completed"
}
```

Or if printing fails:

```http
POST /api/print-jobs/{print_job_id}/failed
```

**Response:**
```json
{
    "success": true,
    "message": "Print job marked as failed"
}
```

## API Endpoints

### Test Connection
- **URL:** `GET /api/print-jobs/test-connection`
- **Purpose:** Get Pusher configuration and test connectivity

### Get Printer Jobs
- **URL:** `GET /api/print-jobs/printer/{printer_id}/jobs`
- **Purpose:** Get all pending print jobs for a specific printer

### Update Print Job Status
- **URL:** `POST /api/print-jobs/{print_job_id}/complete`
- **URL:** `POST /api/print-jobs/{print_job_id}/failed`
- **Purpose:** Update the status of a print job

## Desktop Application Flow

1. **Startup:** Call test connection to get Pusher config
2. **Initialize Pusher:** Set up Pusher client with received config
3. **Listen for Jobs:** Subscribe to 'print-jobs' channel
4. **Process Jobs:** When a job is received, print it using the payload
5. **Update Status:** Mark job as completed or failed

## Example Implementation

```javascript
class PrintJobHandler {
    constructor(branchId) {
        this.branchId = branchId;
        this.pusher = null;
        this.channel = null;
    }

    async initialize() {
        // Test connection and get Pusher config
        const response = await fetch(`/api/print-jobs/test-connection?branch=${this.branchId}`);
        const data = await response.json();

        if (data.pusher_enabled) {
            this.initializePusher(data.pusher_config);
        }
    }

    initializePusher(config) {
        this.pusher = new Pusher(config.key, {
            cluster: config.cluster,
            encrypted: true
        });

        this.channel = this.pusher.subscribe(config.channel);
        this.channel.bind(config.event, (data) => {
            this.handlePrintJob(data);
        });
    }

    async handlePrintJob(data) {
        try {
            // Print the job using the payload
            await this.print(data.payload);
            
            // Mark as completed
            await fetch(`/api/print-jobs/${data.print_job_id}/complete`, {
                method: 'POST'
            });
        } catch (error) {
            // Mark as failed
            await fetch(`/api/print-jobs/${data.print_job_id}/failed`, {
                method: 'POST'
            });
        }
    }

    async print(payload) {
        // Implement your printing logic here
        // payload.text contains the ESC/POS commands
        console.log('Printing:', payload.text);
    }
}

// Usage
const handler = new PrintJobHandler(1);
handler.initialize();
```

## Security Considerations

- The Pusher key is public and safe to use in client applications
- Print job data is broadcast to all subscribers of the 'print-jobs' channel
- Consider implementing additional authentication if needed for your use case

## Troubleshooting

1. **No events received:** Check if Pusher is enabled in the response
2. **Connection issues:** Verify the cluster and key are correct
3. **Print jobs not updating:** Ensure you're calling the status update endpoints 
