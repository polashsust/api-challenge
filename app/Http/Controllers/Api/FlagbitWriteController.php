<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlagbitWriteController extends Controller
{
    /**
     * Assign (enable) a FlagBit to a transaction.
     * Requires a token with ist_masterkey = 1.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function setFlagbit(Request $request): JsonResponse
    {
        return $this->handleFlagbitUpdate(
            request: $request,
            modus: 1 // 1 = set / assign
        );
    }

    /**
     * Deactivate (remove) a FlagBit from a transaction.
     * Requires a token with ist_masterkey = 1.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function removeFlagbit(Request $request): JsonResponse
    {
        return $this->handleFlagbitUpdate(
            request: $request,
            modus: 2 // 2 = remove / disable
        );
    }

    /**
     * Unified handler for setting or removing flagbits.
     *
     * @param Request $request
     * @param int $modus 1 = set, 2 = remove
     * @return JsonResponse
     */
    private function handleFlagbitUpdate(Request $request, int $modus): JsonResponse
    {
        // Check masterkey access
        if ((int) $request->input('api_user_is_master') !== 1) {
            return response()->json(['error' => 'Access denied: masterkey required'], 403);
        }

        // Validate input
        $data = $this->validateFlagbitRequest($request);

        // Extract flagbit, unless removing (modus = 2)
        $flagbit = $modus === 1 ? $data['flagbit'] : null;

        return $this->executeFlagbitProcedure(
            datensatzId:  $data['trans_id'],
            flagbit:      $flagbit,
            modus:        $modus,
            bearbeiterId: (int) $request->input('api_user_bearbeiter_id')
        );
    }

    /**
     * Validates required inputs for flagbit update.
     *
     * @param Request $request
     * @return array{trans_id: int, flagbit: int}
     */
    private function validateFlagbitRequest(Request $request): array
    {
        return $request->validate([
            'trans_id' => 'required|integer',
            'flagbit'  => 'required|integer',
        ]);
    }

    /**
     * Executes the DB stored procedure to set/remove a flagbit.
     *
     * @param int $datensatzId
     * @param int|null $flagbit
     * @param int $modus
     * @param int $bearbeiterId
     * @return JsonResponse
     */
    private function executeFlagbitProcedure(
        int $datensatzId,
        ?int $flagbit,
        int $modus,
        int $bearbeiterId
    ): JsonResponse {
        DB::statement("SET @code = 0, @msg = ''");

        DB::select("
            CALL stamd_aendern_erstellen_flagbit_ref(
                2, ?, ?, ?, ?, @code, @msg
            )
        ", [$datensatzId, $flagbit, $modus, $bearbeiterId]);

        $result = DB::selectOne("SELECT @code AS fehler_code, @msg AS fehler_text");

        return response()->json([
            'status'  => $result->fehler_code === 0 ? 'success' : 'error',
            'code'    => $result->fehler_code,
            'message' => $result->fehler_text,
        ]);
    }
}
