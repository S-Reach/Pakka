<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutRequest extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'bank_name',
        'account_number',
        'account_holder_name',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}