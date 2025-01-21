<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use function Pest\Laravel\json;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $offers=  Offer::with('store')->where('available',true)->get();
    $categories = Category::all();
      return response()->json([
        'offers'=>$offers,
        'categories'=>$categories,
        'timeStamp' => now()->timestamp * 1000,
        ],200);
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
    public function show($id)
    {

     try {
        $offer = Offer::with('store')->findOrFail($id);
        return response()->json([
        'data'=>$offer
        ],200);
         } catch (ModelNotFoundException $er) {
        throw $er;
     }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Offer $Offer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Offer $Offer)
    {
        //
    }
}
