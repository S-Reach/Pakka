<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Follow extends Model
{
    protected $fillable = [
        'follower_id',
        'following_id'
    ];

     public function follower()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    // 👇 user being FOLLOWED
    public function following()
    {
        return $this->belongsTo(User::class, 'following_id');
    }
}
