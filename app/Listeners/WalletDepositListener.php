<?php

namespace App\Listeners;

use App\Events\WalletDeposit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WalletDepositListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WalletDeposit $event): void
    {
        //
    }
}
