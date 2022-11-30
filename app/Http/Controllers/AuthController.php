<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB,Validator,Auth,Session,Hash};
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(){
        $fullUrl=request()->prev_url;

        if (isset(request()->prev_url))
            session(['prev_url' => $fullUrl]);

        return view('auth.login');
    }

    public function authenticate(Request $request) {

        $credentials = $request->validate([
            'email' => 'bail|required|email',
            'password' => 'bail|required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (!empty(session('prev_url')))
                return redirect((string)session()->pull('prev_url', 'default'));

            return redirect()->intended('dashboard');
        }

        Session::flash('status', 'Login failed');
        return back();
    }

    public function signUp(){
        return view('auth.register');
    }

    public function register(Request $request) {
        DB::beginTransaction();
        $userData = $request->validate([
            'name' => 'required|string|max:200|regex:/(^([a-zA-Z ]+)(\d+)?$)/u',
            'email' => 'required|string|email:rfc,dns|unique:users',
            'password' => 'required|string|min:8|regex:/(^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])(?=\S*[\W])\S*$)/u',
            'confirm_password'  => 'required|same:password'
            //'contact' => 'required|max:20|regex:/[0-9]/'
        ]);

        $userData['password'] = bcrypt($request->password);

        $user = User::create($userData);

        if ($user) {
            // $user->sendEmailVerificationNotification();
            DB::commit();
            Session::flash('status', 'User added successfully');
            return back();
        }

        DB::rollBack();
        Session::flash('status', 'Registration Failed');
        return back();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function redirectToGoogle(){
        #return Socialite::driver('google')->stateless()->redirect();
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback() {
        try {
            $googleAccount = Socialite::driver('google')->user();
            #$googleAccount = Socialite::driver('google')->stateless()->user();
            #dd(json_encode($googleAccount));
            if ($this->validateEmailExistence($googleAccount->getEmail())) {
                Session::flash('status', 'Email already exist please enter a new one');
                return redirect('/login');
            }

            $user = User::updateOrCreate([
                'google_id' => $googleAccount->getId(),
                //'email' => $googleAccount->getEmail(),
            ], [
                'name' => $googleAccount->getName(),
                'password' => bcrypt($googleAccount->getId()),
                'email' => $googleAccount->getEmail(),
                'avatar' => $googleAccount->getAvatar()
            ]);

            Auth::login($user);

            if (!empty(session('prev_url')))
                return redirect((string)session()->pull('prev_url', 'default'));

            return redirect('/dashboard');

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function loginUsingFacebook() {
        return Socialite::driver('facebook')->redirect();
    }

    public function callbackFromFacebook() {
        try {
            $facebookAccount = Socialite::driver('facebook')->user();
            #dd(json_encode($facebookAccount));

            if ($this->validateEmailExistence($facebookAccount->getEmail()) ) {
                Session::flash('status', 'Email already exist please enter a new one');
                return redirect('/login');
            }

            $user = User::updateOrCreate([
                'facebook_id' => $facebookAccount->getId(),
                //'email' => $facebookAccount->getEmail()
            ],[
                'name' => $facebookAccount->getName(),
                'email' => $facebookAccount->getEmail(),
                'password' => bcrypt($facebookAccount->getId()),
                'avatar' => $facebookAccount->getAvatar()
            ]);

            Auth::login($user);

            if (!empty(session('prev_url')))
                return redirect((string)session()->pull('prev_url', 'default'));

            return redirect('/dashboard');

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function validateEmailExistence($email) {
        $existingEmail = User::where(['email'=>$email, 'facebook_id'=>null, 'google_id'=>null])->first();

        return ($existingEmail) ? true : false;
    }

}
