<?php

namespace Unit\App\Services\Slots;

use App\Services\Slots\Payline;

class PaylineTest extends \TestCase
{
    /**
     * Testing functionality of generating paylines using Payline class
     */
    function testGeneratePaylines(): void
    {

        $result = (new Payline(4))->generatePaylines()->getPaylines();

        $this->assertEquals(count($result), 4, 'Asserting result array has 4 rows');
        $this->assertEquals(count($result[0]), 5, 'Asserting result has 5 columns');

    }

    /**
     * @throws \Exception
     */
    function testForcePaylines(): void
    {

        $paylines = [
            [0, 4, 6],
            [4, 7, 5]
        ];

        $result = (new Payline(2))->forcePaylines($paylines)->getPaylines();

        $this->assertEquals($result, $paylines);
    }
}
