<?php

namespace Unit\App\Services\Slots;

use App\Services\Slots\Board;
use App\Services\Slots\SlotsGenerator;

class SlotGeneratorTest extends \TestCase
{
    /**
     * Test if slot generator created required slot items
     */
    function testPlay(): void
    {

        $result = SlotsGenerator::play();

        $this->assertInstanceOf(Board::class, $result, 'Asserting that slot generator returns instance of Board class.');
        $this->assertEquals(15, count($result->getSlots()), 'Asserting that slot items has 15 elements.');
    }

    /**
     *
     */
    function testPlayFromItems(): void
    {

        $items = [
            'A', 'B', 'C', 'D', 'E'
        ];

        $result = SlotsGenerator::playFromItems($items);

        $this->assertInstanceOf(Board::class, $result, 'Asserting that slot generator returns instance of Board class.');
        $this->assertEquals(15, count($result->getSlots()), 'Asserting that board has 15 slot items.');
        $this->assertFalse(in_array('F', $result->getSlots()), 'Asserting that invalid element is not present in board.');
    }

}
