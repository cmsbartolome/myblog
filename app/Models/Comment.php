<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\{Auth,DB};

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'comment',
        'post_id',
        'created_by',
    ];

    protected $guarded = [];

    public function post(){
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function isLike($comment_id, $post_id) {
       $query = Like::where([
           'comment_id' => $comment_id,
           'post_id' => $post_id,
           'liked_by' => Auth::id()
       ])->first();

       return $query ? true : false;
    }

    public function commentTotalLikes($comment_id) {
        return DB::table('likes')->where('comment_id',$comment_id)->count();
    }
}
