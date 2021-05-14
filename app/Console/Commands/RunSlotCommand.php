<?php
namespace App\Console\Commands;
use App\Services\Slots\PayLine;
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
     */
    public function handle() : void
    {
        $betAmount = $this->ask('Enter bet amount');

        $board = SlotsGenerator::play();
        $payline = (new PayLine(5))->generatePaylines();

        $slotMachine = new SlotMachine($board, $payline, $betAmount);
        $slotMachine->determineWinningLines();

        $payLines = $slotMachine->formatWiningPayLines();
        $winningAmount = ($slotMachine->calculateWinningAmount())->getWinningAmount();

        if($winningAmount === 0) {
            $this->info('Try again :)');
        }

        if($winningAmount > 0) {
            $this->info(json_encode($board->formatBoard()));
            $this->newLine();

            $this->info(json_encode($payLines));
            $this->newLine();

            $this->info($slotMachine->getBetAmount());
            $this->newLine();

            $this->info($winningAmount);
        }
    }
}
