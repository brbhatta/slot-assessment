<?php

namespace Unit\App\Services\Slots;

use App\Exceptions\BoardSlotsException;
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

        $this->assertInstanceOf(Board::class, $result, 'Asserting that slot generator returns instance of Board class on play.');
        $this->assertCount(15, $result->getSlots(), 'Asserting that slot items has 15 elements on play.');
    }

    /**
     *
     * @throws \App\Exceptions\BoardSlotsException
     */
    function testPlayFromItems(): void
    {
        $items = [
            'A', 'B', 'C', 'D', 'E'
        ];

        $result = SlotsGenerator::playFromItems($items);

        $this->assertInstanceOf(Board::class, $result, 'Asserting that slot generator returns instance of Board class on playFromItems.');
        $this->assertCount(15, $result->getSlots(), 'Asserting that board has 15 slot items on playFromItems.');
        $this->assertFalse(in_array('F', $result->getSlots()), 'Asserting that invalid element is not present in board on playFromItems.');
    }

    /**
     *
     * @throws \App\Exceptions\BoardSlotsException
     */
    function testPlayWithDefinedSlots() {
        $slots = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'B', 'B', 'A', 'A', 'A'];

        $result = SlotsGenerator::playWithDefinedSlots($slots);

        $this->assertInstanceOf(Board::class, $result, 'Asserting that slot generator return instance of Board class on playWithDefinedSlots.');
        $this->assertCount(15, $result->getSlots(), 'Asserting that board has 15 slot items on playWithDefinedSlots.');
        $this->assertFalse(in_array('X', $result->getSlots()), 'Asserting that invalid element is not present in board on playWithDefinedSlots.');
    }

}
