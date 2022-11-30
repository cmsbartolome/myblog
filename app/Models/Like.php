<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $fillable = [
        'post_id',
        'comment_id',
        'liked_by'
    ];

    protected $guarded = [];

    public function post(){
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public function comment(){
        return $this->belongsTo(Comment::class, 'comment_id', 'id');
    }
}
