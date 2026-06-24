<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Story;
use App\Models\User;
use App\Models\Chapter;

class MyLibrary extends Model
{
    protected $table = 'mylibrary';

    protected $fillable = [
        'user_id',
        'story_id',
        'last_chapter_id',
    ];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lastChapter()
    {
        return $this->belongsTo(Chapter::class, 'last_chapter_id');
    }
}
