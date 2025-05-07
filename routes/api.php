<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TimeController;
use App\Http\Controllers\Api\FlagbitController;
use App\Http\Controllers\Api\FlagbitWriteController;

// All routes in this group are protected by custom token authentication middleware
Route::middleware('auth.token')->group(function () {

    // GET /api/time
    // Returns the current server time
    Route::get('/time', [TimeController::class, 'getTime']);

    // POST /api/flagbits
    // Retrieves active flagbits for a specific transaction
    Route::post('/flagbits', [FlagbitController::class, 'getFlagbits']);

    // POST /api/flagbit/set
    // Sets (enables) a flagbit for a transaction
    // Requires a masterkey token
    Route::post('/flagbit/set', [FlagbitWriteController::class, 'setFlagbit']);

    // POST /api/flagbit/remove
    // Removes (deactivates) a flagbit from a transaction
    // Requires a masterkey token
    Route::post('/flagbit/remove', [FlagbitWriteController::class, 'removeFlagbit']);
});
