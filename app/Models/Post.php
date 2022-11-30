<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'category_id',
        'keywords',
        'author'
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function comments(){
        return $this->hasMany(Comment::class, 'post_id', 'id')->latest();
    }

    public function likes() {
        return $this->belongsTo(Like::class, 'post_id', 'id');
    }

    public function postTotalLikes() {
        return $this->hasMany(Like::class, 'post_id', 'id')->whereNull('comment_id')->count();
    }
}
