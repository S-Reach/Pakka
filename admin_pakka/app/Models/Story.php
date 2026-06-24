<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Chapter;
use App\Models\MyLibrary;
use App\Models\Like;
use App\Models\Comment;
use App\Models\StoryRating;
use App\Models\StoryReport;

class Story extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'synopsis',
        'language',
        'genres',
        'tags',
        'format', 
        'cover_image',
        'is_premium',
        'price',
        'story_progress', // published, draft
        'views',
        'likes',
        'ratings_count',
        'earnings',
        'content',
        'story_status', // complete, ongoing
        'story_approval_status', // pending, approved, rejected
        'is_hidden',
    ];

    protected $casts = [
        'genres' => 'array',
        'tags' => 'array',
        'is_premium' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    public function library()
    {
        return $this->hasMany(MyLibrary::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)
            ->whereNull('chapter_id')
            ->latest();
    }

    public function ratings()
    {
        return $this->hasMany(StoryRating::class);
    }

    public function reports()
    {
        return $this->hasMany(StoryReport::class);
    }
    
}