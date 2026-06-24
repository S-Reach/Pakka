<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReadingPreference extends Model
{
    protected $fillable = [
        'user_id',
        'theme',
        'font',
        'khmer_font',
        'size'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}