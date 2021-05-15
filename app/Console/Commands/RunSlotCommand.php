<?php
namespace App\Console\Commands;
use App\Services\Slots\Payline;
use App\Services\Slots\SlotMachine;
use App\Services\Slots\SlotsGenerator;
use Illuminate\Console\Command;

class RunSlotCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'slot:play';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Run board slot and calculate the wining";

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \App\Exceptions\BoardSlotsException
     */
    public function handle() : void
    {
        $betAmount = $this->ask('Enter bet amount');

        $board = SlotsGenerator::play();
//        $payline = (new Payline(5))->generatePaylines();

        /**
         * To have predefined paylines
         */
        $predefinedPaylines = [
            [0, 3, 6, 9, 12],
            [1, 4, 7, 10, 13],
            [2, 5, 8, 11, 14],
            [0, 4, 8, 10, 12],
            [2, 4, 6, 10, 14]
        ];
        $payline = (new Payline())->forcePaylines($predefinedPaylines);
        $slotMachine = new SlotMachine($board, $payline, $betAmount);

        $response = [
            'board' => $board->formatBoard(),
            'paylines' => $slotMachine->getFormattedWiningLines(),
            'bet_amount' => $slotMachine->getBetAmount(),
            'total_win' => $slotMachine->getWinningAmount()
        ];

        $this->line(json_encode($response,JSON_PRETTY_PRINT));
    }
}
