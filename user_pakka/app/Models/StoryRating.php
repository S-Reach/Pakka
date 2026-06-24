<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Story;
use App\Models\User;

class StoryRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'story_id',
        'rating',
    ];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
