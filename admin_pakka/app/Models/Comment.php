<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Story;
use App\Models\Chapter;
use App\Models\CommentLike;
use App\Models\CommentReport;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'story_id',
        'chapter_id',
        'parent_id',
        'comment',
        'is_hidden',
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

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')
            ->latest();
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    public function reports()
    {
        return $this->hasMany(CommentReport::class);
    }
    
    public function isLikedByUser()
    {
        return $this->likes()->where('user_id', auth()->id())->exists();
    }
}
