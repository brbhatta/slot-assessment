<?php

namespace App\Services\Slots;

class Payline
{
    /**
     * @var array
     */
    private $paylines = [];

    /**
     * Each payline will have 5 index (items)
     * @var int
     */
    private $columns = 5;

    /**
     * Determine how many paylines can be defined
     * @var int
     */
    private $paylineRowsCount;

    /**
     * Payline constructor.
     * @param int $paylineRowsCount
     */
    function __construct(int $paylineRowsCount = 5)
    {
        $this->paylineRowsCount = $paylineRowsCount;
    }

    /**
     * Allow paylines to be specifically determined to avoid arbitrary values
     *
     * @param array $paylines
     * @return Payline
     */
    public function forcePaylines(array $paylines): Payline
    {
        if ($this->paylineRowsCount !== count($paylines)) {
            throw new \Exception('Rows does not match with paylines provided');
        }
        $this->paylines = $paylines;

        return $this;
    }

    /**
     * Generate arbitrary paylines
     *
     * @return $this
     */
    public function generatePaylines(): Payline
    {
        for ($i = 0; $i < $this->paylineRowsCount; $i++) {

            $this->paylines[$i] = [];

            for ($j = 0; $j < $this->columns; $j++) {
                $randIndex = rand(0, 14);

                //To ensure that same index is not repeated in a payline
                if (!in_array($randIndex, $this->paylines[$i])) {
                    $this->paylines[$i][] = $randIndex;
                } else {
                    $j--;
                }
            }

        }

        return $this;
    }

    /**
     * @return array
     */
    public function getPaylines(): array
    {
        return $this->paylines;
    }
}
