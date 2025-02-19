<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'body',
        'cover_image',
        'pinned'
    ];

    protected static function booted()
    {
        static::created(function ($post) {
            cache()->forget('stats');
        });

        static::deleted(function ($post) {
            cache()->forget('stats');
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}