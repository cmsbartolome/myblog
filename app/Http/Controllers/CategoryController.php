<?php

namespace App\Http\Controllers;

use App\Models\{Category,UserPreference};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB,Validator,Auth,Cache};


class CategoryController extends Controller
{
    public function index() {
        $categories = Category::orderBy('created_at', 'DESC')->paginate(5);
        $cat = Category::latest()->limit(5)->get();
        $total_rec = Category::count();
        $list = Category::latest()->take(10)->get();
        $user_pref = UserPreference::where(['user_id' => Auth::id(), 'key'=>'cat_view'])->first();

       return view('categories.index', compact('categories', 'cat', 'user_pref', 'total_rec', 'list'));
    }

    public function loadMoreCategories(Request $request) {
        if($request->ajax()){
            $data = $request->all();
//           return ['success' => 0, 'message' => json_encode($data)];
            $offset = (int)$data['offset'];
            $posts = Category::latest()->skip($offset)->take(5)->get();

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

            $posts = Category::latest()->when(isset($lastId) && $lastId > 0,function ($query) use($lastId){
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

    public function store(Request $request){
        if ($request->ajax()) {
            DB::beginTransaction();

            $data = $request->all();
//            return ['success' => 0, 'message' => json_encode($data)];
            $attributes = [
                'title' => 'Title',
                'description' => 'Description',
            ];

            $rules = [
                'title' => 'required|unique:categories,title|string',
                'description' => 'required',
            ];

            $validator = Validator::make($data, $rules, [], $attributes);

            if ($validator->fails()) {
                return ['success' => 0, 'message' => $validator->messages()->first()];
            }

            $catData = [
                'title' => (string) $data['title'],
                'description' => (string) $data['description'],
                'created_by' => Auth::id()
            ];

            $category = Category::create($catData);

            if ($category) {
                DB::commit();
                $this->clearCache();
                if (Cache::has('categories')) {
                    //Cache::pull('categories');
                    Cache::forget('categories');
                }

                return ['success' => 1, 'message' => 'Category successfully created', 'data' => json_encode($category)];
            }

            DB::rollBack();
            return ['success' => 0, 'message' => 'Unable to create category, please try again'];

        }
        abort(403);

    }

    public function view(Request $request) {
        if ($request->ajax()) {
            $id = (int)$request->get('id');
            $category = Category::findOrFail($id);

            if ($category) {
                $data = [
                    'response' => 'success',
                    'title' => (string)e($category->title),
                    'description' => (string)e($category->description),
                ];
                return json_encode($data);
            }

            $data = [
                'response' => 'failed',
                'message' => 'No Category Found'
            ];
            return json_encode($data);
        }
    }

    public function update(Request $request){
        if ($request->ajax()) {
            DB::beginTransaction();
            $data = $request->all();

            // return ['success' => 0, 'message' => json_encode($data)];

            if (isset($data['status'])) {
                $category = Category::find($data['id']);
                $category->active = (int)$data['status'];

                if ($category->save()) {
                    DB::commit();
                    $this->clearCache();
                    return ['success' => 1, 'message' => 'Category status updated successfully', 'data' => json_encode($category)];
                }

                DB::rollBack();
                return ['success' => 0, 'message' => 'Unable to update category status, please try again'];

            }

            $attributes = [
                'title' => 'Title',
                'description' => 'Description',
            ];

            $rules = [
                'title' => 'required|unique:categories,title,id'.e($data['id']),
                'description' => 'required',
            ];

            $validator = Validator::make($data, $rules, [], $attributes);

            if ($validator->fails()) {
                return ['success' => 0, 'message' => $validator->messages()->first()];
            }

            $category = Category::find($data['id']);
            $category->title = $data['title'];
            $category->description = $data['description'];

            if ($category->save()) {
                DB::commit();
                $this->clearCache();
                return ['success' => 1, 'message' => 'Category updated successfully', 'data' => json_encode($category)];
            }

            DB::rollBack();
            return ['success' => 0, 'message' => 'Unable to update category, please try again'];

        }

        abort(403);

    }

    public function delete(Request $request) {
        if ($request->ajax()) {
            DB::beginTransaction();
            $id = (int)$request->get('id');
            $category = Category::find($id);

            if ($category->delete()) {
                DB::commit();
                $this->clearCache();
                return ['success' => 1, 'message' => 'Category deleted successfully'];
            }

            DB::rollBack();
            return ['success' => 0, 'message' => 'Failed to delete category'];
        }
        abort(403);
    }

    public function clearCache() {
        if (Cache::has('categories')) {
            //Cache::pull('categories');
            $value = Cache::forget('categories');
        }
    }
}
