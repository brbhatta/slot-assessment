<?php

namespace App\Services\Slots;

class SlotsGenerator
{
    private $symbols;

    /**
     * Slots is a list of random symbols
     * @var array
     */
    private $slots = [];

    /**
     * Assuming rows will be 3 always
     * @var int
     */
    private $rows = 3;

    /**
     * Assuming columns will be 5 always
     * @var int
     */
    private $columns = 5;

    /**
     * SlotsGenerator constructor.
     * @param string[] $symbols
     * @param array $slots
     */
    private function __construct(
        array $symbols = ['9', '10', 'J', 'Q', 'K', 'A', 'cat', 'dog', 'monkey', 'bird'],
        array $slots = []
    )
    {
        $this->symbols = $symbols;
        $this->generateSlots($slots);
    }

    /**
     * Board is collection of slots
     * @return Board
     * @throws \App\Exceptions\BoardSlotsException
     */
    public function getBoard(): Board
    {
        return new Board($this->slots, $this->rows, $this->columns);
    }

    /**
     * @param array $slots
     */
    private function generateSlots(array $slots = []): void
    {
        $this->slots = $slots;

        if (empty($this->slots)) {
            $count = $this->rows * $this->columns;

            for ($i = 0; $i < $count; $i++) {
                array_push($this->slots, $this->symbols[array_rand($this->symbols)]);
            }
        }

    }

    /**
     * @return Board
     * @throws \App\Exceptions\BoardSlotsException
     */
    public static function play(): Board
    {
        return (new SlotsGenerator())->getBoard();
    }

    /**
     * @param $symbols
     * @return Board
     * @throws \App\Exceptions\BoardSlotsException
     */
    public static function playFromItems(array $symbols): Board
    {
        return (new SlotsGenerator($symbols))->getBoard();
    }

    /**
     * @param $slots
     * @return Board
     * @throws \App\Exceptions\BoardSlotsException
     */
    public static function playWithDefinedSlots(array $slots): Board
    {
        return (new SlotsGenerator([], $slots))->getBoard();
    }
}
