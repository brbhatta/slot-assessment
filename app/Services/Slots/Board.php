<?php

namespace App\Services\Slots;

use App\Exceptions\BoardSlotsException;

class Board
{
    /**
     * @var array
     */
    private $slots;

    /**
     * @var int
     */
    private $rows;

    /**
     * @var int
     */
    private $columns;

    /**
     * Will hold slot items by row and columns in multi-dimensional array
     * @var array
     */
    private $resultBoard = [];

    /**
     * Temp variable, used while placing slot items in board by row and columns
     * @var array
     */
    private $row = [];

    /**
     * Board constructor.
     * @param array $slots
     * @param int $rows
     * @param int $columns
     */
    function __construct(array $slots, int $rows, int $columns)
    {

        if (count($slots) !== $rows * $columns) {
            throw new BoardSlotsException('Solt items could not fit into rows and columns');
        }

        $this->slots = $slots;
        $this->rows = $rows;
        $this->columns = $columns;
    }

    /**
     * @return array
     */
    public function getSlots(): array
    {
        return $this->slots;
    }

    /**
     * @return int|int
     */
    public function getRows(): int
    {
        return $this->rows;
    }

    /**
     * @return int|int
     */
    public function getColumns(): int
    {
        return $this->columns;
    }

    /**
     * Will return single dimensional array (board) with slot items placed.
     * @return array
     */
    public function formatBoard(): array
    {

        for ($i = 0; $i < $this->rows; $i++) {
            $this->generateRow($i);
        }

        return array_merge(...$this->resultBoard); //Flatten the array to make it single dimensional
    }

    /**
     * Formats array into row colums based on number of rows defined.
     * Sets resultBoard with slot items in columns for provided row index
     * @param $index
     */
    private function generateRow(int $index): void
    {
        if (count($this->row) === $this->columns) {
            $this->resultBoard[] = $this->row;
            $this->row = [];
            return;
        }

        $this->row[] = $this->slots[$index];

        $index += $this->rows;
        $this->generateRow($index);

        return;
    }
}
