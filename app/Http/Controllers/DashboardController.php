<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\{Category,Post,UserPreference,SubCategory,User};
use Illuminate\Support\Facades\{Auth,Session,DB,Validator,Cache,Hash};


class DashboardController extends Controller
{
    public function index() {
        $categories = Category::whereCreatedBy(Auth::id())->count();
        $subcategories = SubCategory::whereCreatedBy(Auth::id())->count();
        $articles = Post::whereAuthor(Auth::id())->count();
        $actUsers = User::whereActive(1)->count();
        $inactUsers = User::whereActive(0)->count();

        $year = ['2022', '2023', '2024'];
        $user = [];
        foreach ($year as $key => $value) {
            $user[] = User::where(\DB::raw("DATE_FORMAT(created_at, '%Y')"),$value)->count();
        }

        $years = json_encode($year,JSON_NUMERIC_CHECK);
        $users = json_encode($user,JSON_NUMERIC_CHECK);

//        $views = Post::select(DB::raw("SUM(views) as count"))
//            ->orderBy("created_at")
//            ->groupBy(DB::raw("year(created_at)"))
//            ->get()->toArray();
//        $viewer = array_column($viewer, 'count');
//
//        $click = Click::select(DB::raw("SUM(numberofclick) as count"))
//            ->orderBy("created_at")
//            ->groupBy(DB::raw("year(created_at)"))
//            ->get()->toArray();
//        $click = array_column($click, 'count');
//
//        return view('chartjs')
//            ->with('viewer',json_encode($viewer,JSON_NUMERIC_CHECK))
//            ->with('click',json_encode($click,JSON_NUMERIC_CHECK));

        return view('dashboard', compact('categories', 'articles', 'subcategories', 'actUsers', 'inactUsers', 'years','users'));
    }

    public function updateUserPreference(Request $request) {
        if ($request->ajax()) {
            $data = $request->all();

            if ($data['key'] == "cat_view" || $data['key'] == "subcat_view") {
                if (!in_array($data['value'], ['table', 'grid', 'list'])) {
                    return ['success' => 0, 'message' => 'Invalid value'];
                }
            }

            $userPref = UserPreference::updateOrCreate([
                //Add unique field combo to match here
                //For example, perhaps you only want one entry per user:
                'key' => $data['key'],
                'user_id' => Auth::id()
            ], [
                'value' => $data['value'],
            ]);

            return ['success' => 1, 'message' => 'Success'];

        }
        abort('403');
    }

    public function showUserProfile() {
        $user = User::findOrFail(Auth::id());
        return view('user.profile', compact('user'));
    }

    public function updateUserProfile(Request $request){
        $data = $request->all();
        DB::beginTransaction();

       $validate_data = $request->validate([
            'name' => isset($data['name']) ? 'required|string|max:200|regex:/(^([a-zA-Z ]+)(\d+)?$)/u' : '',
            'password' => isset($data['password']) ? 'required|string|min:8|regex:/(^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$)/u' : '',
            'confirm_password'  => isset($data['password']) ? 'required|same:password' : '',
            'old_password'  => isset($data['password']) ? 'required' : '',
            'image' => $request->hasFile('image') ? 'image|mimes:jpg,png,jpeg|max:2048' : ''
            //'contact' => 'required|max:20|regex:/[0-9]/'
        ]);

        $user = User::findOrFail(Auth::id());

        if (isset($data['old_password'])) {
            if (Hash::check($data['old_password'], $user->password)) {
                $user->password = bcrypt($data['password']);
            } else {
                DB::rollBack();
                Session::flash('failed', 'Current password is incorrect');
                return back();
            }
        }

        if (isset($data['name'])) {
            $user->name = (string)$data['name'];
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $mediaName = hash('sha256', Auth::id());
            $user->clearMediaCollection($mediaName);
            $user->clearMediaCollection('images');
            $user->addMediaFromRequest('image')->toMediaCollection($mediaName);
        }

        if ($user->save()) {
            DB::commit();
            Session::flash('success', 'Information updated successfully');
            return back();
        }

        DB::rollBack();
        Session::flash('failed', 'Failed. Unable to update information');
        return back();
    }

}
