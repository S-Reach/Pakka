<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Story;
use App\Models\Comment;
use App\Models\ChapterPurchase;
use App\Models\User;


class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'story_id',
        'chapter_id',
        'chapter_number',
        'title',
        'content',
        'story_progress', // draft, published
        'views',
        'is_premium',
        'price',
        'chapter_approval_status', // pending, approved, rejected
        'rejection_reason',
        'rejection_highlights',
        'is_hidden',
    ];

    /**
     * Chapter belongs to a Story
     */
    public function story()
    {
        return $this->belongsTo(Story::class);
    }

    // COMMENTS
    public function comments()
    {
        return $this->hasMany(Comment::class)
            ->latest();
    }

    // PURCHASES
    public function purchases()
    {
        return $this->hasMany(ChapterPurchase::class);
    }

    // USER
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}