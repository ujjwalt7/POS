<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PrintJobController;
use App\Http\Middleware\DesktopUniqueKeyMiddleware;
use App\Http\Middleware\CorsMiddleware;

// called by Electron every X seconds
Route::middleware(DesktopUniqueKeyMiddleware::class)->group(function () {
    Route::get('/test-connection', [PrintJobController::class, 'testConnection']);

    //Single job pull
    Route::get('/print-jobs/pull', [PrintJobController::class, 'pull']);

    //Multiple job pull
    Route::get('/print-jobs/pull-multiple', [PrintJobController::class, 'pullMultiple']);

    Route::get('/printer-details', [PrintJobController::class, 'printerDetails']);
    // mark a job done/failed
    Route::patch('/print-jobs/{printJob}', [PrintJobController::class, 'update']);
    Route::get('/print-jobs/printer/{printerId}/jobs', [PrintJobController::class, 'getPrinterJobs']);

    // Mark print job as completed
    Route::post('/print-jobs/{printJobId}/complete', [PrintJobController::class, 'complete']);
    Route::post('/print-jobs/{printJobId}/failed', [PrintJobController::class, 'failed']);
    Route::get('/print-jobs/pending/{printId}', [PrintJobController::class, 'pending']);
});
