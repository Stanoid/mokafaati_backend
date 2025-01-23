<?php

namespace App\Http\Controllers;

use App\Models\User;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Bavix\Wallet\External\Dto\Extra;
use Bavix\Wallet\External\Dto\Option;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Bavix\Wallet\Exceptions\ConfirmedInvalid;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Artisan;
use App\Models\Transact;
function roundTo2Decimals($number) {
    return number_format((float)$number, 2, '.', '');
  }

class WalletController extends Controller
{



    public function balanceOnly(Request $request)
    {
        //return
        $user = Auth::user();
      //  $transactions = collect($user->transactions);

        try {


            return response()->json([
                "balance" =>roundTo2Decimals(number: $user->wallet->balance/100) ,
               ]);
        } catch (ConfirmedInvalid $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 400);
        } catch (BalanceIsEmpty $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 400);
        }
    }

    public function balance(Request $request)
    {
        //return
        $user = Auth::user();
        $transactions = collect($user->transactions);

        try {


            return response()->json([
                "balance" =>roundTo2Decimals(number: $user->wallet->balance/100) ,
               "transactions" => $transactions->sortByDesc("created_at"),
                'unconfirmed' => roundTo2Decimals($transactions->where('confirmed', false)->sum('amount')/100),
            ]);
        } catch (ConfirmedInvalid $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 400);
        } catch (BalanceIsEmpty $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 400);
        }
    }

    public function deposit($id)
    {



        try {

            $user = User::findOrFail($id);
            $user->deposit(10000, null, false);
            //return $user->wallet->balance;
            return response()->json([
                'balance' => $user->wallet->balance,
                'nameee' => $user->name,
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 400);
        }
    }

    public function refresh($id)
    {
        try {

            $user = User::findOrFail($id);
            //$user->wallet->refreshBalance();
            //return $user->wallet->balance;

            $transactions = collect($user->transactions)->where("confirmed", false);
            //$user->confirm($transactions->->first());

            foreach ($transactions as $key => $transaction) {
                $user->confirm($transaction);
            }
            return response()->json([
                'balance' => $user->wallet->balance,
                'nameee' => $user->name,
                "message" => "Confirmed all transactions"
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 400);
        }
    }


    public function transfer($amount, $to)
    {
        $user = Auth::user();
        $user2 = User::find($to);
        $metaContract =  new Extra(
            deposit: new Option(
                [
                    'type' => 'direct-transfer',
                    'user'=> $user2

                ],
                true // confirmed
            ),
            withdraw: new Option(
                [
                    'type' => 'direct-transfer',
                    'user'=> $user2
                ],
               true // confirmed
            ),
            extra: [
                'msg' => 'hello world',
            ],
        );

        try {

            $transfer = $user->transfer($user2, $amount, $metaContract);
            return response()->json([
                'message' => 'transfered',
                'transfer' => $transfer,
            ], 200);
        } catch (InsufficientFunds $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 400);
        } catch (BalanceIsEmpty $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 400);
        }




        // $transfer = $user->balance;


    }

    public function transactions(){
        $transactions = Auth::user()->transactions;
        return response()->json([
            "data"=> array($transactions)
            ],200);
    }

    public function resetDB(Request $request){


        Artisan::call('migrate:fresh');
        Artisan::call('db:seed');
        return response()->json([
            'message' => 'Database reset and seeded successfully'
        ], 200);
    }



    public function decrypt(Request $request){

        $crypt = Crypt::decryptString($request->input('payload'));
        return response()->json([
            'message'=> 'Success',
            'decryped'=> $crypt
            ],200);
    }


}
