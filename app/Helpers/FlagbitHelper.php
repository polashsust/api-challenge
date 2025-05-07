<?php

namespace App\Helpers;

class FlagbitHelper
{
    /**
     * Retrieves the name of a constant based on the provided flagbit value.
     *
     * @param int $flagbit The integer value representing the flagbit.
     * @return string|null The name of the constant if found, or null if no matching constant exists.
     */
    public static function getConstantName(int $flagbit): ?string
    {
        $map = [
            1 => 'TRANSACTION_FLAG_CLEARING',
            2 => 'TRANSACTION_FLAG_GUARANTEE',
            3 => 'TRANSACTION_FLAG_3DSECURE',
            4 => 'TRANSACTION_FLAG_EXT_API',
            5 => 'TRANSACTION_FLAG_DEMO',
            6 => 'TRANSACTION_FLAG_AUTHORIZATION',
            7 => 'TRANSACTION_FLAG_ACCRUAL',
            8 => 'TRANSACTION_FLAG_STAKEHOLDER_EVALUATED',
            9 => 'TRANSACTION_FLAG_BASKET_EVALUATED',
            10 => 'TRANSACTION_FLAG_BASKET_ITEM_EVALUATED',
            11 => 'TRANSACTION_FLAG_SECUCORE',
            12 => 'TRANSACTION_FLAG_CHECKOUT',
            13 => 'TRANSACTION_FLAG_LVP',
            14 => 'TRANSACTION_FLAG_TRA',
            15 => 'TRANSACTION_FLAG_MIT',
        ];

        return $map[$flagbit] ?? null;
    }
}
