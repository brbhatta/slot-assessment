<?php

namespace Unit\App\Services\Slots;

use App\Services\Slots\Board;

class BoardTest extends \TestCase
{
    /**
     * @throws \App\Exceptions\BoardSlotsException
     */
    public function testBoard(): void
    {
        $slots = range(16, 30);
        $board = new Board($slots, 3, 5);

        $this->assertEquals($board->getSlots(), $slots, 'Asserting board returns correct values.');
        $this->assertEquals($board->getRows(), 3, 'Asserting board has 3 rows.');
        $this->assertEquals($board->getColumns(), 5, 'Asserting board has 5 columns.');
    }

    /**
     * @throws \App\Exceptions\BoardSlotsException
     */
    public function testFormatBoard(): void
    {
        $slots = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];
        $formattedBoard = (new Board($slots, 3, 3))->formatBoard();

        $this->assertEquals(['A', 'D', 'G', 'B', 'E', 'H', 'C', 'F', 'I'], $formattedBoard, 'Asserting that board is formatted to display mode.');
    }
}
