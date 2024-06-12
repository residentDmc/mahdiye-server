<?php

namespace App\Console\Commands;

use App\Jobs\CheckReserveStatus;
use Illuminate\Console\Command;

class ExpiredReservesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check-expired-reserves';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        CheckReserveStatus::dispatch();
    }
}
