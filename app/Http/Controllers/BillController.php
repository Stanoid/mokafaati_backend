<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Http\Requests\StoreBillRequest;
use App\Http\Requests\UpdateBillRequest;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Bavix\Wallet\External\Dto\Option;
use Carbon\Carbon;
use Bavix\Wallet\External\Dto\Extra;
use Bavix\Wallet\Models\Transaction;
use App\Notifications\WithdrawSuccessful;
use App\Notifications\DepositSuccessful;
use App\Events\BillDevided;
use App\Jobs\DepositNotificationJob;
class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(StoreBillRequest $request)
    {
        $res = Store::where('mid', '=', $request->mid)->first();
        return response()->json($res);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBillRequest $request)
    {
        $auth_user =  Auth::user();
        $store = Store::where('mid', '=', $request->mid)->first();

        if (!$store) {
            return response()->json([
                "message" => "This store is not registered to mokafaati",
                "code" => "SCN_2" //store not registered
            ], 400);
        }
        $offer = $store->offers->where('available', true)->first();

        $purchasedOn = Carbon::parse($request->purchasedOn);
        $endDate = Carbon::parse($offer->end_date);


        if ($purchasedOn->lt($endDate)) {

            if ($store) {
                $bill = Bill::where('rawBill', $request->rawBill)->count();
                // return $bill;


                //  if($bill === 0){
                if (true) {
                    $response = $auth_user->bills()->create([
                        'mid' => $request->mid,
                        'points' => ($request->points * ($offer->cash_back / 100)), //beware of confusion
                        'amount' => $request->points,
                        'purchasedOn' => $request->purchasedOn,
                        'nameOnBill' => $request->nameOnBill,
                        'rawBill' => $request->rawBill,
                    ]);

                    try {


                        $metaContract =  ["store" => $store, 'type' => 'offer-direct-desposit'];


                        $user = User::findOrFail($auth_user->id);
                        $transaction = $user->depositFloat($response->points, $metaContract, true); //true for confirmed
                        $finalBill = $response->update(['transaction_id' => $transaction->id]);
                    } catch (ModelNotFoundException $e) {
                        return response()->json([
                            'message' => $e->getMessage(),
                            'code' => $e->getCode(),
                        ], 400);
                    }
                    return response()->json([
                        "data" => $response,
                        'offer' => $offer,
                        'store' => $store
                        // "request"=> $request->all(),
                    ], 200);
                } else {
                    return response()->json([
                        "message" => "This bill is already scanned",
                        "code" => "SCN_1" //bill exists
                    ], 400);
                }
            } else {
                return response()->json([
                    "message" => "This store is not registered to mokafaati",
                    "code" => "SCN_2" //store not registered
                ], 400);
            }
        } else {

            return response()->json([
                "message" => "This recipt is expired",
                "code" => "SCN_3" //expired recipt
            ], 400);
        }

        // return response()->json([
        //     "offer end"=> $endDate,
        //     "recipt date"=>$purchasedOn
        //    // "request"=> $request->all(),
        // ],200);








    }


    public function list(Request $request)
    {
        $response = Bill::with('share', 'store')->where("user_id", Auth::user()->id)->get();

        // Loop through the response and get the transaction by transaction_id
        $response->each(function ($bill) {
            $transaction = Transaction::find($bill->transaction_id);
            $bill->transaction = $transaction;
        });

        return response()->json([
            "data" => $response,
            // "request" => $request->all(),
        ], 200);
    }



    /**
     * Display the specified resource.
     */

    public function get(Request $request)
    {

        try {

            $bill = Bill::findOrFail($request->input("billId"));
            if ($bill->user_id == Auth::user()->id) {
                return response()->json([
                    "data" => $bill,
                    "friends" => User::where("id", '!=', Auth::user()->id)->select(['id', 'name', 'email'])->get()
                ], 200);
            } else {

                return response()->json([
                    "message" => "You are not authorized to view this record",
                ], 403);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ], 400);
        }
    }

    public function devide(Request $request)
    {




        $billobj = (object)$request->billId;
        // return response()->json([
        //     'data'=> $billobj->id
        //     ],200);
        $bill = Bill::findOrFail($billobj->id);

        // if($bill->status == 'shared'){
        //     return response()->json([
        //         'message'=> 'This bill is already shared',
        //         "code" => "DVI_1" //bill already shared
        //     ],400);
        // }



        //  return response()->json([
        //              'store'=> $bill->store
        //          ],200);




        $friendsArray = [];
        foreach ($request->selected as $select) {


            $obj = (object) $select;
            $user2 = User::find($obj->id);
            $metaContract =  new Extra(
                deposit: new Option(
                    [
                        'type' => 'offer-share-desposit',
                        'user' => Auth::user(),
                        'store' => $bill->store



                    ],
                    true // confirmed
                ),
                withdraw: new Option(
                    [
                        'type' => 'offer-share-withdraw',
                        'user' => $user2,
                        'store' => $bill->store

                    ],
                    true // confirmed
                ),
                extra: [
                    'bill_id' => $bill->id,
                ],
            );


            $amount = ($billobj->points / (count($request->selected) + 1)) * 100;
            $transfer = Auth::user()->transfer($user2, $amount, $metaContract);
            $arr =  array_push($friendsArray, [
                'user' => $user2->name,
                'id' => $user2->id,
                'amount' => ($billobj->points / (count($request->selected) + 1)) * 100
            ]);
        }
        $billshare = $bill->share()->create([
            'friends' => json_encode($friendsArray),
        ]);
        $bill->select('status')->update(["status" => 'shared']);

        // Auth::user()->notify(new WithdrawSuccessful($amount, $user2->name));

        // $user2->notify(new DepositSuccessful($amount, Auth::user()->name));

        DepositNotificationJob::dispatch($bill->id);

        return response()->json([
            'data' => $arr
        ], 200);
    }



    public function show(Bill $bill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bill $bill)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBillRequest $request, Bill $bill)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bill $bill)
    {
        //
    }
}
