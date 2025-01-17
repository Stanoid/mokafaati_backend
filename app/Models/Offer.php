<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Store;
class Offer extends Model
{
     /** @use HasFactory<\Database\Factories\OfferFactory> */
     use HasFactory;



     public function store ():HasOne
     {
   return $this->hasOne(Store::class,'id','store_id');
     }
}
