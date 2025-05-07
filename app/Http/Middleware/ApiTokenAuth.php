<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ApiTokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Step 1: Extract token
        $token = $request->bearerToken();

        if (!$token) {
            return $this->unauthorized('API token required');
        }

        // Step 2: Validate token using stored procedure
        $userData = $this->validateToken($token);

        if (!$userData) {
            return $this->unauthorized('Invalid or expired API token', 403);
        }

        // Step 3: Inject user-specific data into request
        $this->attachUserDataToRequest($request, $userData);

        return $next($request);
    }

    /**
     * Validate the API token using stored procedure.
     *
     * @param string $token
     * @return object|null
     */
    private function validateToken(string $token): ?object
    {
        try {
            $result = DB::select("CALL validate_apikey(?)", [$token]);
            return $result[0] ?? null;
        } catch (\Exception $e) {
            Log::error('Token validation failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Inject validated user data into the request.
     *
     * @param Request $request
     * @param object $userData
     * @return void
     */
    private function attachUserDataToRequest(Request $request, object $userData): void
    {
        $request->merge([
            'api_user_vertrag_id'    => $userData->vertrag_id,
            'api_user_bearbeiter_id' => $userData->bearbeiter_id,
            'api_user_is_master'     => $userData->ist_masterkey,
        ]);
    }

    /**
     * Return a JSON unauthorized response.
     *
     * @param string $message
     * @param int $code
     * @return Response
     */
    private function unauthorized(string $message, int $code = 401): Response
    {
        Log::warning('Unauthorized access attempt', ['message' => $message]);
        return response()->json(['error' => $message], $code);
    }
}
