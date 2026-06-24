<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Chapter;

class ChapterReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'chapter_id',
        'reason',
        'details',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
    
}
