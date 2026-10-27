<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
     protected $fillable = [
        'wallet_id',
        'transaction_id',
        'type',
        'amount',
        'balance_before',
        'balance_after'
    ];


    public function wallet()
{
    return $this->belongsTo(Wallet::class);
}
}
