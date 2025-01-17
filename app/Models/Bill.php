<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Bavix\Wallet\Models\Transaction;
use App\Models\Share;
use App\Models\User;
use App\Models\Store;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Bill extends Model
{
    /** @use HasFactory<\Database\Factories\BillFactory> */
    use HasFactory;

    protected $fillable = [
        'mid',
        'points',
        'purchasedOn',
        'nameOnBill',
        'status',
        'amount',
        'rawBill',

        'transaction_id'
    ];






    /**
     * Get the user that owns the Bill
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany{
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the share associated with the Bill
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function share(): HasOne
    {
        return $this->hasOne(Share::class);
    }

    /**
     * Get the store associated with the Bill
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function store(): HasOne
    {
        return $this->hasOne(Store::class, 'mid', 'mid');
    }
}


