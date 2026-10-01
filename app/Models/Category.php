<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name' , 'slug'];

    // one category hasmany posts
    public function posts(){
        return $this->hasMany(Post::class);
    }
}
