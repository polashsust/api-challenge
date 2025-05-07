<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class TimeService
{
    /**
     * Get the current server time via stored procedure.
     *
     * @return string|null
     */
    public function getCurrentServerTime(): ?string
    {
        $result = DB::select("CALL GetServerTime()");
        return $result[0]->server_time ?? null;
    }
}

