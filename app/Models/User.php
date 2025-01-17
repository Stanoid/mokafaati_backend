<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Bavix\Wallet\Interfaces\Confirmable;
use Bavix\Wallet\Traits\CanConfirm;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Traits\HasWalletFloat;
use App\Models\Bill;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Interfaces\WalletFloat;

class User extends Authenticatable implements Wallet, Confirmable,WalletFloat
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens,CanConfirm,HasWalletFloat;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'fcm_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function routeNotificationForFcm()
    {
        return $this->fcm_token;
    }




    public function hasRole($role)
    {
     if($this->role==$role){
        return true;
     }else{
        return false;
     }
    }


    /**
     * Get all of the bills for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bills(): HasMany
    {
        return $this->hasMany(Bill::class);
    }

}
