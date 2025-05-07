<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Helpers\FlagbitHelper;


class FlagbitHelperTest extends TestCase
{
    /**
     * Tests that the getConstantName method returns the correct constant name.
     *
     * @return void
     */
    public function testGetConstantNameReturnsCorrectConstant()
    {
        $this->assertEquals('TRANSACTION_FLAG_CLEARING', FlagbitHelper::getConstantName(1));
        $this->assertEquals('TRANSACTION_FLAG_GUARANTEE', FlagbitHelper::getConstantName(2));
        $this->assertEquals('TRANSACTION_FLAG_3DSECURE', FlagbitHelper::getConstantName(3));
        $this->assertEquals('TRANSACTION_FLAG_EXT_API', FlagbitHelper::getConstantName(4));
        $this->assertEquals('TRANSACTION_FLAG_DEMO', FlagbitHelper::getConstantName(5));
        $this->assertEquals('TRANSACTION_FLAG_AUTHORIZATION', FlagbitHelper::getConstantName(6));
        $this->assertEquals('TRANSACTION_FLAG_ACCRUAL', FlagbitHelper::getConstantName(7));
        $this->assertEquals('TRANSACTION_FLAG_STAKEHOLDER_EVALUATED', FlagbitHelper::getConstantName(8));
        $this->assertEquals('TRANSACTION_FLAG_BASKET_EVALUATED', FlagbitHelper::getConstantName(9));
        $this->assertEquals('TRANSACTION_FLAG_BASKET_ITEM_EVALUATED', FlagbitHelper::getConstantName(10));
        $this->assertEquals('TRANSACTION_FLAG_SECUCORE', FlagbitHelper::getConstantName(11));
        $this->assertEquals('TRANSACTION_FLAG_CHECKOUT', FlagbitHelper::getConstantName(12));
        $this->assertEquals('TRANSACTION_FLAG_LVP', FlagbitHelper::getConstantName(13));
        $this->assertEquals('TRANSACTION_FLAG_TRA', FlagbitHelper::getConstantName(14));
        $this->assertEquals('TRANSACTION_FLAG_MIT', FlagbitHelper::getConstantName(15));
    }

    /**
     * Tests that the getConstantName method returns null when provided with an invalid Flagbit.
     *
     * @return void
     */
    public function testGetConstantNameReturnsNullForInvalidFlagbit()
    {
        $this->assertNull(FlagbitHelper::getConstantName(0));
        $this->assertNull(FlagbitHelper::getConstantName(16));
        $this->assertNull(FlagbitHelper::getConstantName(-1));
    }
}