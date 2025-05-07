<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TimeService;
use Illuminate\Http\JsonResponse;

class TimeController extends Controller
{
    protected TimeService $timeService;

    /**
     * Inject the TimeService via constructor.
     */
    public function __construct(TimeService $timeService)
    {
        $this->timeService = $timeService;
    }

    /**
     * Handle GET /time
     * 
     * Returns the current server time via stored procedure call.
     *
     * @return JsonResponse
     */
    public function getTime(): JsonResponse
    {
        $serverTime = $this->timeService->getCurrentServerTime();

        return response()->json([
            'server_time' => $serverTime
        ]);
    }
}
