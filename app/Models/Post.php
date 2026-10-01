<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'content',
        'cover_image',
        'published_at',
        'status',
    ];

    protected function casts(): array{
        return[
            'published_at' => 'datetime',
        ];
    }

    // owners
    public function user(){
        return $this->belongsTo(User::class);
    }
    // one post belongs to only one category
    public function category(){
        return $this->belongsTo(Category::class);
    }

    //children

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    // one post may have many tags
    public function tags(){
        return $this->belongsToMany(Tag::class);
    }
}
