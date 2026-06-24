<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Story;
use App\Models\Chapter;

class ReadingHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'story_id',
        'chapter_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    } 

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
