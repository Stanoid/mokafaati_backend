<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client as GoogleClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Notifications\DepositSuccessful;

class FcmController extends Controller
{
    public function updateDeviceToken(Request $request)
    {
        $request->validate([
            'fcm_token' => 'required|string',
        ]);

      // return response()->json(['message' => $request->user()],200);


      $token = $request->user()->update(['fcm_token' => $request->fcm_token]);

        return response()->json(['message' => $token],200);

    }

    public function sendFcmNotification(Request $request)
    {

        $user2 = User::find($request->user_id);
        $notif=  $user2->notify(new DepositSuccessful(34));
        return response()->json(['message' => $notif ], 200);


    }
}
