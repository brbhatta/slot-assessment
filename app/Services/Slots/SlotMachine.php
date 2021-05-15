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
    private $paylines;

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
     * @var array
     */
    private $formattedWiningLines = [];

    /**
     * @var int
     */
    private $winningAmount = 0;

    /**
     * SlotMachine constructor.
     * @param Board $board
     * @param Payline $payline
     * @param int $betAmount
     */
    function __construct(Board $board, Payline $payline, int $betAmount)
    {
        $this->slots = $board->getSlots();
        $this->paylines = $payline->getPaylines();
        $this->betAmount = $betAmount * 100;

        $this->determineWinningLines();
        $this->calculateWinningAmount();
    }

    /**
     * Determine based on slot items in board, which paylines are payable
     * @return array
     */
    private function determineWinningLines(): void
    {
        foreach ($this->paylines as $k => $payline) {

            /*
             * Get slot items in particular payline
             */
            $boardPaylineSet = array_intersect_key($this->slots, array_flip($payline));

            $stage = []; // Temp variable to identify consecutive slot items
            $results = []; // Hold consecutive slot items

            /**
             * Loop through payline and set result if there are 3 or more consecutive slot item
             */
            foreach ($boardPaylineSet as $val) {

                if (count($stage) > 0 && $val != $stage[count($stage) - 1]) {
                    //Ensure there are more than 2 consecutive item in a payline
                    if (count($stage) > 2) {
                        $results = $stage;
                    }
                    $stage = [];
                }

                $stage[] = $val;

                // To ensure that if all items are consecutive in a payline, results is well populated.
                if(count($stage) === count($boardPaylineSet)) {
                    $results = $stage;
                }
            }

            if (!empty($results)) {
                $this->winningLines[$k] = count($results);
            }
        }

        $this->formatWiningPaylines();

        return;
    }

    /**
     * Calculates the wining amount in cents (Euro * 100) based on applicable paylines
     *
     * @return SlotMachine
     */
    private function calculateWinningAmount(): void
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

        return;
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
                $winPercentage = 1000;
                break;
            default:
                $winPercentage = 0;
        }
        return $winPercentage;
    }

    /**
     * Format winning paylines to have desirable printing
     */
    private function formatWiningPaylines(): void
    {
        foreach ($this->winningLines as $k => $count) {
            $this->formattedWiningLines[] = [implode(', ', $this->paylines[$k]) => $count];
        }

        return;
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
     * @return int
     */
    public function getBetAmount(): int
    {
        return $this->betAmount;
    }

    /**
     * @return array
     */
    public function getFormattedWiningLines()
    {
        return $this->formattedWiningLines;
    }
}
