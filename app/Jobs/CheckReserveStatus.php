<?php

namespace App\Jobs;

use App\Models\Reserve;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckReserveStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Reserve::whereIn('status', ['active', 'inactive'])
            ->take(100)
            ->whereRaw('CONCAT(date, " ", end_time) < now()')
            ->update(['status' => 'expired']);
    }
}
