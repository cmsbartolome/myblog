<?php

namespace App\Http\Controllers;

use App\Models\{Category,Post,Comment,Like};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB,Validator,Auth,Cache};
use Illuminate\Support\Str;

class FrontController extends Controller
{
    public function index() {
        $topArticles = Post::whereActive(1)->orderBy('views', 'DESC')->take(15)->get();
        $newArticles = Post::whereActive(1)->latest()->take(15)->get();
        $categories = Category::whereActive(1)->get();

      return view('index', compact('topArticles', 'newArticles', 'categories'));
    }

    public function aboutUs() {

    }

    public function contactUs() {

    }

    public function searchArticle(Request $request) {
        $keyword = $request->search ?? null;
        $articles = Post::whereActive(1)
            ->where(function ($query) use ($keyword) {
                $query->where('vehicle_name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('keywords', 'LIKE', '%' . $keyword . '%');
            })->select('id', 'title', 'views', 'category_id', 'keywords', 'active', 'author')
            ->distinct('title')
            ->orderBy('title', 'ASC')

            ->paginate(50);

        //return view('search', compact('articles'));
    }

    public function viewArticle($title, $id) {
        $article = Post::where(['title'=>$title, 'active'=>1])->orWhere('id', $id)->firstOrFail();
        $article->increment('views');
        $article->update();

        return view('article', compact('article'));
    }

    public function addComment(Request $request) {
        if ($request->ajax()) {
            DB::beginTransaction();

            $data = $request->all();
//            return ['success' => 0, 'message' => json_encode($data)];
            $attributes = [
                'post' => 'Post ID',
                'comment' => 'Comment',
            ];

            $rules = [
                'post' => 'required|numeric',
                'comment' => 'required',
            ];

            $validator = Validator::make($data, $rules, [], $attributes);

            if ($validator->fails()) {
                return ['success' => 0, 'message' => $validator->messages()->first()];
            }

            $catData = [
                'comment' => $data['comment'],
                'post_id' => $data['post'],
                'created_by' => Auth::id()
            ];

            $comment = Comment::create($catData);

            if ($comment) {
                DB::commit();
                $comment['name'] = auth()->user()->name;
                $comment['avatar'] = auth()->user()->avatar;
                $comment['like'] = false;
                $comment['totalLikes'] = (new Comment())->commentTotalLikes($comment->id);
                return ['success' => 1, 'message' => 'Comment added successfully', 'data' => json_encode($comment)];
            }

            DB::rollBack();
            return ['success' => 0, 'message' => 'Unable to add comment, please try again'];

        }
        abort(403);
    }

    public function likeComment(Request $request) {
        if ($request->ajax()) {
            DB::beginTransaction();
            $data = $request->all();
//             return ['success' => 0, 'message' => json_encode($data)];
            $attributes = [
                'p_id' => 'Post',
                'cmt_id' => 'Comment'
            ];

            $rules = [
                'p_id' => 'required',
                'cmt_id' => 'required',
                'action' => 'required'
            ];

            $validator = Validator::make($data, $rules, [], $attributes);

            if ($validator->fails()) {
                return ['success' => 0, 'message' => $validator->messages()->first()];
            }

            switch($data['action']) {
                case "like":
                    $like = Like::firstOrCreate([
                            'post_id' => (int)$data['p_id'],
                            'comment_id' => (int)$data['cmt_id'],
                            'liked_by' => Auth::id()
                        ],[
                            'post_id' => (int)$data['p_id'],
                            'comment_id' => (int)$data['cmt_id'],
                            'liked_by' => Auth::id()
                        ]);

                    if ($like) {
                        DB::commit();
                        $comDetails = [
                            'id' => $like->comment->id,
                            'comment' => $like->comment->comment,
                            'name' => $like->comment->user->name,
                            'avatar' => $like->comment->user->avatar,
                            'like'=>true,
                            'totalLikes' => (new Comment())->commentTotalLikes($data['cmt_id'])
                        ];

                        return ['success' => 1, 'message' => 'Successfully like comment', 'data' => json_encode($comDetails)];
                    }

                    break;
                case "unlike":
                    $like = Like::where([
                        'post_id' => (int)$data['p_id'],
                        'comment_id' => (int)$data['cmt_id'],
                        'liked_by' => Auth::id(),
                    ])->first();

                    if ($like->delete()) {
                        DB::commit();
                        $comDetails = [
                            'id' => $like->comment->id,
                            'comment' => $like->comment->comment,
                            'name' => $like->comment->user->name,
                            'avatar' => $like->comment->user->avatar,
                            'like'=>false,
                            'totalLikes' => (new Comment())->commentTotalLikes($data['cmt_id'])
                        ];

                        return ['success' => 1, 'message' => 'Successfully dislike comment', 'data' => json_encode($comDetails)];
                    }

                    break;

                default: //do nothing

            }

            DB::rollBack();
            return ['success' => 0, 'message' => 'Unable to like comment, please try again'];

        }
        abort(403);
    }

}
