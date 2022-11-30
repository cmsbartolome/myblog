<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB,Validator,Auth,Cache};
use App\Models\{Category,UserPreference,SubCategory};

class SubCategoryController extends Controller
{
    public function index() {
        $subcategories = SubCategory::orderBy('created_at', 'DESC')->paginate(5);
        $subcat = SubCategory::orderBy('id', 'desc')->limit(5)->get();
        $list = SubCategory::latest()->take(10)->get();
        $categories = Category::whereActive(1)->get();
//        $categories = Cache::rememberForever('categories', function () {
//            return Category::whereActive(1)->get();
//        });

        $user_pref = UserPreference::where(['user_id' => Auth::id(), 'key'=>'subcat_view'])->first();

        return view('sub-categories.index', compact('subcategories', 'subcat', 'user_pref', 'categories','list'));
    }

    public function loadMoreSubCategories(Request $request) {
        $data = $request->all();
        $output = '';
        $last_id = '';
        $id = (int) $data['id'];

        if($request->ajax()){

            if($id > 0){
                $subcategories = SubCategory::
                //where('status', '=', 'ACTIVE')
                where('id', '<', $id)
                    ->orderBy('id', 'DESC')
                    ->take(5)
                    ->get();
            } else {
                $subcategories = SubCategory::
                //where('status', '=', 'ACTIVE')
                orderBy('id', 'DESC')
                    ->take(5)
                    ->get();
            }

            if(!$subcategories->isEmpty()){

                foreach ($subcategories as $SubCategory) {
                    $enc_cat_id = $SubCategory->id;

                    $output .= '<div class="col s12 m4">
                        <div class="card">
                            <div class="card-image">
                                <img src="https://picsum.photos/150/150?random=3">
                                <span class="card-title">'.e($SubCategory->title).'</span>
                            </div>
                            <div class="card-content">
                                <p>'.e($SubCategory->description).'</p>
                            </div>
                            <div class="card-action">
                                <a href="#">View</a>
                                <a href="#">Edit</a>
                                <a href="#">Delete</a>
                            </div>
                        </div>
                    </div>';

                    $last_id = $enc_cat_id;

                }
                $output .= '<div class="row" id="remove-row" >
                                     <center>
                                     <div class="col s12 m12">
                                        <button class="waves-effect waves-light btn red" id="btn-more" data-id="' . e($enc_cat_id) . '" >Load more</button>
                                     </div>
                                     </center>
                                </div>';

            } else {
                $output .= '<div class="row">
                                <center>
                                    <div class="col s12 m12">

                                    </div>
                                </center>
                            </div>';
            }
            echo $output;
        }

    }

    public function store(Request $request){
        if ($request->ajax()) {
            DB::beginTransaction();

            $data = $request->all();

            $attributes = [
                'title' => 'Title',
                'category_id' => 'Related Category',
                'description' => 'Description',
            ];

            $rules = [
                'title' => 'required|unique:sub_categories,title|string',
                'category_id' => 'required|exists:categories,id',
                'description' => 'required',
            ];

            $validator = Validator::make($data, $rules, [], $attributes);

            if ($validator->fails()) {
                return ['success' => 0, 'message' => $validator->messages()->first()];
            }

            $catData = [
                'title' => $data['title'],
                'description' => $data['description'],
                'category_id' => $data['category_id'],
                'created_by' => Auth::id()
            ];

            $SubCategory = SubCategory::create($catData);

            if ($SubCategory) {
                DB::commit();
                $SubCategory['category_name'] = optional($SubCategory->category)->title;
                return ['success' => 1, 'message' => 'SubCategory successfully created', 'data' => json_encode($SubCategory)];
            }

            DB::rollBack();
            return ['success' => 0, 'message' => 'Unable to create subcategory, please try again'];

        }
        abort(403);

    }

    public function view(Request $request) {
        if ($request->ajax()) {
            $id = (int)$request->get('id');
            $SubCategory = SubCategory::findOrFail($id);

            if ($SubCategory) {
                $data = [
                    'response' => 'success',
                    'title' => (string)e($SubCategory->title),
                    'category' => (string)e($SubCategory->category_id),
                    'description' => (string)e($SubCategory->description),
                ];
                return json_encode($data);
            }

            $data = [
                'response' => 'failed',
                'message' => 'No SubCategory Found'
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
                $SubCategory = SubCategory::find($data['id']);
                $SubCategory->active = (int)$data['status'];

                if ($SubCategory->save()) {
                    DB::commit();
                    $SubCategory['category_name'] = optional($SubCategory->category)->title;
                    return ['success' => 1, 'message' => 'SubCategory status updated successfully', 'data' => json_encode($SubCategory)];
                }

                DB::rollBack();
                return ['success' => 0, 'message' => 'Unable to update subCategory status, please try again'];

            }

            $attributes = [
                'title' => 'Title',
                'category_id' => 'Related Category',
                'description' => 'Description',
            ];

            $rules = [
                'title' => 'required|unique:categories,title,id'.e($data['id']),
                'category_id' => 'required|exists:categories,id',
                'description' => 'required',
            ];

            $validator = Validator::make($data, $rules, [], $attributes);

            if ($validator->fails()) {
                return ['success' => 0, 'message' => $validator->messages()->first()];
            }

            $SubCategory = SubCategory::find($data['id']);
            $SubCategory->title = $data['title'];
            $SubCategory->category_id = $data['category_id'];
            $SubCategory->description = $data['description'];

            if ($SubCategory->save()) {
                DB::commit();
                $SubCategory['category_name'] = optional($SubCategory->category)->title;
                return ['success' => 1, 'message' => 'SubCategory updated successfully','data' => json_encode($SubCategory)];
            }

            DB::rollBack();
            return ['success' => 0, 'message' => 'Unable to update subcategory, please try again'];

        }

        abort(403);

    }

    public function delete(Request $request) {
        if ($request->ajax()) {
            $id = (int)$request->get('id');
            $SubCategory = SubCategory::find($id);

            if ($SubCategory->delete()) {
                return ['success' => 1, 'message' => 'SubCategory deleted successfully'];
            }

            return ['success' => 0, 'message' => 'Failed to delete subcategory'];
        }
        abort(403);
    }

}
