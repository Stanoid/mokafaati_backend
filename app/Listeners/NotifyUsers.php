<?php

namespace App\Listeners;

use App\Events\BillDevided;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Offer;
use App\Notifications\DepositSuccessful;
use App\Models\Bill;
use App\Notifications\WithdrawSuccessful;
use App\Models\User;

class NotifyUsers
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
    public function handle(BillDevided $event): void
    {



        try {
            $billid = $event->id;
            $bill = Bill::with('share')->find($billid);
            // dd(json_decode($bill->share->friends));

            foreach(json_decode($bill->share->friends) as $share){
                $sender = User::find($bill->user_id);
                $recipent = User::find($share->id);
                $sender->notify(new WithdrawSuccessful($share->amount, $recipent->name));
                $recipent->notify(new DepositSuccessful($share->amount, $sender->name));
             }


        } catch (\Throwable $th) {
            //throw $th;
        }



          //dd($bill);

         // Auth::user()->notify(new WithdrawSuccessful($amount, $user2->name));

        // $user2->notify(new DepositSuccessful($amount, Auth::user()->name));



    }
}
