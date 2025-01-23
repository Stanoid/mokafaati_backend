<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bill;

class BillsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $billId = $request->route('bill'); // Assuming the bill ID is passed as a route parameter
        $bill = Bill::find($billId);

        if ($bill && $bill->user_id == Auth::user()->id) {
            return $next($request);
        } else {
            return response()->json([
                "message" => "You are not authorized to view this record",
            ], 403);
        }
    }
}
