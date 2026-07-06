<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use App\Models\Comment;
use App\Models\MyLibrary;
use App\Models\ReadingPreference;
use App\Models\Follow;
use App\Models\StoryRating;
use App\Models\ReadingHistory;
use App\Models\SavedStory;
use App\Models\ChapterPurchase;
use App\Models\PayoutRequest;
use App\Models\Story;
use App\Models\Chapter;


class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'name',
        'email',
        'password',
        'bio',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function library()
    {
        return $this->hasMany(MyLibrary::class);
    }

    public function readingPreference()
    {
        return $this->hasOne(ReadingPreference::class);
    }

    public function followers()
    {
        return $this->hasMany(Follow::class, 'following_id');
    }

    public function following()
    {
        return $this->hasMany(Follow::class, 'follower_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)
            ->where('is_hidden', false);
    }

    public function ratings()
    {
        return $this->hasMany(StoryRating::class);
    }

    public function readingHistory()
    {
        return $this->hasMany(ReadingHistory::class);
    }

    public function savedStories()
    {
        return $this->hasMany(SavedStory::class);
    }

    public function chapterPurchases()
    {
        return $this->hasMany(ChapterPurchase::class);
    }

    public function payoutRequests()
    {
        return $this->hasMany(PayoutRequest::class);
    }

    public function stories()
    {
        return $this->hasMany(Story::class)
            ->where('is_hidden', false);
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class)
            ->where('is_hidden', false);
    }

    public function isSuspended()
    {
        return $this->suspended_at !== null;
    }

   public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return Storage::disk('spaces')->url($this->avatar);
        }
        return null;
    }
}
