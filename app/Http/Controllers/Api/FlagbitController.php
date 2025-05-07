<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\FlagbitHelper;

class FlagbitController extends Controller
{
    /**
     * Get active FlagBits for a given transaction.
     * 
     * Authorization rules:
     * - Masterkey users have full access
     * - Regular users can only access their own transactions
     * 
     * Uses stored procedure: `get_flagbits_for_transaction`
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getFlagbits(Request $request): JsonResponse
    {
        // Validate required input
        $request->validate([
            'trans_id' => 'required|integer',
        ]);

        // Extract required data from request & token
        $transId    = $request->input('trans_id');
        $vertragId  = $request->input('api_user_vertrag_id');
        $isMaster   = (bool) $request->input('api_user_is_master');

        // Check user authorization for the transaction
        $transaction = DB::table('transaktion_transaktionen')
            ->when(!$isMaster, function ($query) use ($vertragId) {
                $query->where('vertrag_id', $vertragId);
            })
            ->where('trans_id', $transId)
            ->first();

        if (!$transaction) {
            return response()->json([
                'error' => 'Transaction not found or access denied'
            ], 403);
        }

        // Fetch flagbits from stored procedure
        $flagbits = DB::select('CALL get_flagbits_for_transaction(?)', [$transId]);

        // Enrich results with constant names from const.php
        $flagbitsTransformed = $this->mapFlagbitsWithConstants($flagbits);

        // Return structured response
        return response()->json([
            'trans_id' => $transId,
            'flagbits' => $flagbitsTransformed,
        ]);
    }

    /**
     * Format each flagbit by appending its constant name and description.
     *
     * @param array $flagbitRows
     * @return array
     */
    private function mapFlagbitsWithConstants(array $flagbitRows): array
    {
        return array_map(function ($item) {
            return [
                'flagbit'             => $item->flagbit,
                'flagbit_constant'    => FlagbitHelper::getConstantName($item->flagbit),
                'flagbit_description' => $item->flagbit_description,
            ];
        }, $flagbitRows);
    }
}
