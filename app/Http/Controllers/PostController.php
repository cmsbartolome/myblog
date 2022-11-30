<?php

namespace App\Http\Controllers;

use App\Models\{Category,Post,UserPreference};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth,Session,DB,Validator,Cache};


class PostController extends Controller
{
    public function index() {
        $articles = Post::latest()->whereAuthor(Auth::id())->paginate(10);
        $art = Post::latest()->whereAuthor(Auth::id())->take(10)->get();
        $a =  Post::latest()->whereAuthor(Auth::id())->take(10)->get();
        $total_rec = Post::whereAuthor(Auth::id())->count();
        $user_pref = UserPreference::where(['user_id' => Auth::id(), 'key'=>'my_article_view'])->first();

        return view('articles.index',compact('articles', 'user_pref', 'art', 'total_rec', 'a'));
    }

    public function create() {
        $categories = Cache::remember('categories', 15, function () {
            return Category::whereActive(1)->get();
        });
        return view('articles.create', compact('categories'));
    }

    public function store(Request $request){
       if ($request->ajax()) {
           DB::beginTransaction();
           $data = $request->all();
            // return ['success' => 0, 'message' => json_encode($data)];
           $attributes = [
               'title' => 'Title',
               'category_' => 'Category',
               'description' => 'Description',
               'keywords' => 'Keyword',
           ];

           $rules = [
               'title' => 'required|unique:posts',
               'category' => 'required',
               'description' => 'required',
               'keywords' => 'required',
           ];

           $validator = Validator::make($data, $rules, [], $attributes);

           if ($validator->fails()) {
               return ['success' => 0, 'message' => $validator->messages()->first()];
           }

               $postData = [
                   'title' => (string) $data['title'],
                   'description' => (string) $data['description'],
                   'category_id' => $data['category'],
                   'keywords' => json_encode($data['keywords']),
                   'author' => Auth::id()
               ];

                $post = Post::create($postData);

               if ($post) {
                   DB::commit();
                   return ['success' => 1, 'message' => 'Article successfully created'];
               }

               DB::rollBack();
               return ['success' => 0, 'message' => 'Unable to create article, please try again'];

       } else {
           abort(403);
       }
    }

    public function edit($id){
        $article = Post::findOrFail($id);
        $categories = Cache::rememberForever('categories', function () {
            return Category::whereActive(1)->get();
        });

        return view('articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request){
        if ($request->ajax()) {
            DB::beginTransaction();
            $data = $request->all();
            //return ['success' => 0, 'message' => json_encode($data)];

            if (isset($data['status'])) {
                $post = Post::find($data['id']);
                $post->active = (int)$data['status'];

                if ($post->save()) {
                    DB::commit();
                    $post['url'] = route('edit-article', ['id'=>$post->id]);
                    return ['success' => 1, 'message' => 'Article status updated successfully', 'data' => json_encode($post)];
                }

                DB::rollBack();
                return ['success' => 0, 'message' => 'Unable to update article status, please try again'];

            }

            if (isset($data['favorite'])) {
                $post = Post::find($data['id']);
                $post->is_favorite = (int)$data['favorite'];

                if ($post->save()) {
                    DB::commit();
                    $post['url'] = route('edit-article', ['id'=>$post->id]);
                    return ['success' => 1, 'message' => 'Article status updated successfully', 'data' => json_encode($post)];
                }

                DB::rollBack();
                return ['success' => 0, 'message' => 'Unable to update article status, please try again'];
            }

            $attributes = [
                'title' => 'Title',
                'category_' => 'Category',
                'description' => 'Description',
                'keywords' => 'Keyword',
            ];

            $rules = [
                'title' => 'required',//unique:posts,title,'.e($data['title']).',id'.e($data['id'])
                'category' => 'required|exists:categories,id',
                'description' => 'required',
                'keywords' => 'required',
            ];

            $validator = Validator::make($data, $rules, [], $attributes);

            if ($validator->fails()) {
                return ['success' => 0, 'message' => $validator->messages()->first()];
            }

            $post = Post::find($data['id']);
            $post->title = $data['title'];
            $post->category_id = $data['category'];
            $post->keywords = json_encode($data['keywords']);
            $post->description = $data['description'];

            if ($post->save()) {
                DB::commit();
                return ['success' => 1, 'message' => 'Article: '.e($post->title).' updated successfully', 'data' => json_encode($post)];
            }

            DB::rollBack();
            return ['success' => 0, 'message' => 'Unable to update article, please try again'];

        }
        abort(403);
    }

    public function delete(Request $request) {
        if ($request->ajax()) {
            DB::beginTransaction();
            $id = (int)$request->get('id');
            $article = Post::find($id);

            if ($article->delete()) {
                DB::commit();
                return redirect(route('articles'));
            }

            DB::rollBack();
            return ['success' => 0, 'message' => 'Failed to delete article'];
        }
        abort(403);
   }

    public function loadMoreArticles(Request $request) {
       if ($request->ajax()) {
           $data = $request->all();
//           return ['success' => 0, 'message' => json_encode($data)];
           $offset = (int)$data['offset'];
           $posts = Post::whereAuthor(Auth::id())->latest()->skip($offset)->take(10)->get();

           if ($posts) {
               return ['items' => $posts, 'offset' => count($posts)];
           }
       }
       abort('403');
   }

    public function loadMore(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();
//           return ['success' => 0, 'message' => json_encode($data)];
            $limit = (int)$data['limit'];
            $lastId = (int)$data['last_id'];

            $posts = Post::whereAuthor(Auth::id())->latest()->when(isset($lastId) && $lastId > 0,function ($query) use($lastId){
                $query->where('id', '<', $lastId);
            })->take($limit)->get();
//            if($lastId > 0) {
//                $posts = Post::whereAuthor(Auth::id())->latest()->where('id', '<', $lastId)->take($limit)->get();
//            } else {
//                $posts = Post::whereAuthor(Auth::id())->latest()->take($limit)->get();
//            }
            if ($posts) {
                return ['items' => $posts];
            }
        }
        abort('403');
    }

}
