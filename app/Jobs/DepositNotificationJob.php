<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use App\Events\BillDevided;
class DepositNotificationJob implements ShouldQueue
{
    use Queueable;

    /**
     * The payload for the job.
     *
     * @var mixed
     */
    protected $billId;

    /**
     * Create a new job instance.
     */
    public function __construct($billId)
    {
        $this->billId = $billId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        event(new BillDevided($this->billId));
    }
}
