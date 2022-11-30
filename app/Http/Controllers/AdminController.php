<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Request\UserRequest;

class AdminController extends Controller
{
    public function index() {
        $users = User::all();

        return view('users', compact('users'));
    }

    public function create() {
        return view('create-user');
    }

    public function store(UserRequest $request) {
        $user = User::create($request->validated());

    }
}
