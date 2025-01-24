<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Bill;
use App\Notifications\WithdrawSuccessful;
use App\Notifications\DepositSuccessful;
class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $billid = 1;
            $bill = Bill::with('share')->find($billid);
            // dd(json_decode($bill->share->friends));

            foreach(json_decode($bill->share->friends) as $share){
                $sender = User::find($bill->user_id);
                $recipent = User::find($share->id);
                $sender->notify(new WithdrawSuccessful($share->amount, $recipent->name));
                $recipent->notify(new DepositSuccessful($share->amount, $sender->name));
             }


        return response()->json([
            'message' => json_decode($bill->share->friends),
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
