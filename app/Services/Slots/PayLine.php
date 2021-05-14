<?php

namespace App\Services\Slots;

class PayLine
{
    /**
     * @var array
     */
    private $payLines = [];

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
    function __construct(int $paylineRowsCount)
    {
        $this->paylineRowsCount = $paylineRowsCount;
    }

    /**
     * Allow paylines to be specifically determined to avoid arbitrary values
     *
     * @param array $paylines
     * @return PayLine
     */
    public function forcePaylines(array $paylines): PayLine
    {

        if ($this->paylineRowsCount !== count($paylines)) {
            throw new \Exception('Rows does not match with paylines provided');
        }
        $this->payLines = $paylines;

        return $this;
    }

    /**
     * Generate arbitrary paylines
     *
     * @return $this
     */
    public function generatePaylines(): PayLine
    {

        for ($i = 0; $i < $this->paylineRowsCount; $i++) {

            $this->payLines[$i] = [];

            for ($j = 0; $j < $this->columns; $j++) {
                $randIndex = rand(0, 14);

                if (!in_array($randIndex, $this->payLines[$i])) {
                    $this->payLines[$i][] = $randIndex;
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
    public function getPayLines(): array
    {
        return $this->payLines;
    }
}
