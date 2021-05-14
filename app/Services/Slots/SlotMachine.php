<?php

namespace App\Services\Slots;

class SlotMachine
{
    /**
     * @var
     */
    private $slots;

    /**
     * @var
     */
    private $payLines;

    /**
     * Will be in cents because of weird behaviour of floats
     * @var int
     */
    private $betAmount;

    /**
     * @var array
     */
    private $winningLines = [];

    /**
     * @var int
     */
    private $winningAmount = 0;

    /**
     * SlotMachine constructor.
     * @param Board $board
     * @param PayLine $payLine
     * @param int $betAmount
     */
    function __construct(Board $board, PayLine $payLine, int $betAmount)
    {
        $this->slots = $board->getSlots();
        $this->payLines = $payLine->getPayLines();
        $this->betAmount = $betAmount * 100;
    }

    /**
     * Determine based on slot items in board, which payLines are payable
     * @return array
     */
    public function determineWinningLines(): SlotMachine
    {
        foreach ($this->payLines as $k => $payLine) {

            /*
             * Get slot items in particular payLine
             */
            $boardPayLineSet = array_intersect_key($this->slots, array_flip($payLine));;

            $stage = []; // Temp variable to identify consecutive slot items
            $results = []; // Hold consecutive slot items

            /**
             * Loop through payLine and set result if there are 3 or more consecutive slot item
             */
            foreach ($boardPayLineSet as $val) {
                if (count($stage) > 0 && $val != $stage[count($stage) - 1]) {

                    //Ensure there are more than 2 consecutive item in a payLine
                    if (count($stage) > 2) {
                        $results = $stage;
                    }
                    $stage = [];
                }
                $stage[] = $val;
            }

            if (!empty($results)) {
                $this->winningLines[$k] = count($results);
            }
        }

        return $this;
    }

    /**
     * Calculates the wining amount in cents (Euro * 100) based on applicable payLines
     *
     * @return SlotMachine
     */
    public function calculateWinningAmount(): SlotMachine
    {
        if (empty($this->winningLines)) {
            $this->determineWinningLines();
        }

        $totalWiningPercentage = 0;
        foreach ($this->winningLines as $count) {
            $totalWiningPercentage += $this->getWinPercentage($count);
        }

        if ($totalWiningPercentage === 0) {
            $this->winningAmount = 0;
        }

        $this->winningAmount = ($totalWiningPercentage / 100) * $this->betAmount;

        return $this;
    }

    /**
     * @return int
     */
    public function getWinningAmount(): int
    {
        return $this->winningAmount;
    }

    /**
     * @return array
     */
    public function getWinningLines(): array
    {
        return $this->winningLines;
    }

    /**
     * Business logic to calculate winnings.
     *
     * 3 consecutive items in a single payline gets 20% winning
     * 4 consecutive items in a single payline gets 200% winning
     * 5 consecutive items in a single payline gets 2000% winning
     *
     * Total wining will be caculated as sum of all percentage winnings.
     *
     * @param $count
     * @return int
     */
    private function getWinPercentage(int $count): int
    {
        switch ($count) {
            case 3:
                $winPercentage = 20;
                break;
            case 4:
                $winPercentage = 200;
                break;
            case 5:
                $winPercentage = 2000;
                break;
            default:
                $winPercentage = 0;
        }
        return $winPercentage;
    }

    /**
     * Format winning payLines to have desirable printing
     *
     * @return array
     */
    public function formatWiningPayLines(): array
    {

        $results = [];
        foreach ($this->winningLines as $k => $count) {
            $results[] = [implode(', ', $this->payLines[$k]) => $count];
        }

        return $results;
    }

    /**
     * @return int
     */
    public function getBetAmount(): int
    {
        return $this->betAmount;
    }
}
