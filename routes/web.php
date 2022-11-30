<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get('/', ['as' => 'index', 'uses' => 'App\Http\Controllers\FrontController@index']);

Route::get('/login', ['as' => 'login', 'uses' => 'App\Http\Controllers\AuthController@login']);
Route::post('/login', ['as' => 'login', 'uses' => 'App\Http\Controllers\AuthController@authenticate']);

Route::get('auth/google', ['as' => 'google-redirect', 'uses' => 'App\Http\Controllers\AuthController@redirectToGoogle']);
Route::get('auth/google/callback', ['as' => 'google-callback', 'uses' => 'App\Http\Controllers\AuthController@handleGoogleCallback']);

Route::get('auth/facebook', ['as' => 'facebook-redirect', 'uses' => 'App\Http\Controllers\AuthController@loginUsingFacebook']);
Route::get('auth/facebook/callback', ['as' => 'facebook-callback', 'uses' => 'App\Http\Controllers\AuthController@callbackFromFacebook']);

Route::get('/register', ['as' => 'register', 'uses' => 'App\Http\Controllers\AuthController@signUp']);
Route::post('/register', ['as' => 'register', 'uses' => 'App\Http\Controllers\AuthController@register']);

Route::get('/article/{title}/{id}', ['as' => 'view-article', 'uses' => 'App\Http\Controllers\FrontController@viewArticle']);


Route::group(['middleware' => ['auth']], function() {
    Route::post('/add-comment', ['as' => 'store-comment', 'uses' => 'App\Http\Controllers\FrontController@addComment']);

    Route::post('/like-comment', ['as' => 'like-comment', 'uses' => 'App\Http\Controllers\FrontController@likeComment']);

    Route::post('/logout', ['as' => 'logout', 'uses' => 'App\Http\Controllers\AuthController@logout']);

    Route::get('/dashboard', ['as' => 'dashboard',
        'uses' => 'App\Http\Controllers\DashboardController@index']);

    Route::prefix('articles')->group(function () {
        Route::get('/', function () {
            return redirect(route('articles'));
        });

        Route::get('/', ['as' => 'articles', 'uses' => 'App\Http\Controllers\PostController@index']);
        Route::get('/create-article', ['as' => 'create-article', 'uses' => 'App\Http\Controllers\PostController@create']);
        Route::get('/edit-article/{id}', ['as' => 'edit-article', 'uses' => 'App\Http\Controllers\PostController@edit']);
        Route::post('/update-article', ['as' => 'update-article', 'uses' => 'App\Http\Controllers\PostController@update']);
        Route::post('/save-article', ['as' => 'store-article', 'uses' => 'App\Http\Controllers\PostController@store']);
        Route::post('/delete-article', ['as' => 'delete-article', 'uses' => 'App\Http\Controllers\PostController@delete']);
    });

    Route::prefix('user')->group(function () {
        Route::get('/', function () {
            return redirect(route('user-profile'));
        });

        Route::get('/profile', ['as' => 'user-profile', 'uses' => 'App\Http\Controllers\DashboardController@showUserProfile']);
        Route::post('/update-profile', ['as' => 'update-profile', 'uses' => 'App\Http\Controllers\DashboardController@updateUserProfile']);
    });

    //add middleware to only allow admin to access this page
    Route::prefix('categories')->group(function () {
        Route::get('/', function () {
            return redirect(route('categories'));
        });

        Route::get('/', ['as' => 'categories', 'uses' => 'App\Http\Controllers\CategoryController@index']);
        Route::post('/save-category', ['as' => 'store-category', 'uses' => 'App\Http\Controllers\CategoryController@store']);
        Route::get('/view-category', ['as' => 'view-category', 'uses' => 'App\Http\Controllers\CategoryController@view']);
        Route::post('/update-category', ['as' => 'update-category', 'uses' => 'App\Http\Controllers\CategoryController@update']);
        Route::post('/delete-category', ['as' => 'delete-category', 'uses' => 'App\Http\Controllers\CategoryController@delete']);
    });

    Route::prefix('subcategories')->group(function () {
        Route::get('/', function () {
            return redirect(route('subcategories'));
        });

        Route::get('/', ['as' => 'subcategories', 'uses' => 'App\Http\Controllers\SubCategoryController@index']);
        Route::post('/save-subcategory', ['as' => 'store-subcategory', 'uses' => 'App\Http\Controllers\SubCategoryController@store']);
        Route::get('/view-subcategory', ['as' => 'view-subcategory', 'uses' => 'App\Http\Controllers\SubCategoryController@view']);
        Route::post('/update-subcategory', ['as' => 'update-subcategory', 'uses' => 'App\Http\Controllers\SubCategoryController@update']);
        Route::post('/delete-subcategory', ['as' => 'delete-subcategory', 'uses' => 'App\Http\Controllers\SubCategoryController@delete']);
    });

    //add middleware to only allow admin to access this page
    Route::get('/users', ['as' => 'users', 'uses' => 'App\Http\Controllers\UserController@index']);

    Route::post('/user-preference', ['as' => 'user-preference',
        'uses' => 'App\Http\Controllers\DashboardController@updateUserPreference']);

    Route::get('/load-more-categories',['as' => 'load-more-categories', 'uses' => 'App\Http\Controllers\CategoryController@loadMoreCategories']);
    Route::post('/load-more-subcategories',['as' => 'load-more-subcategories', 'uses' => 'App\Http\Controllers\SubCategoryController@loadMoreSubCategories']);
    Route::get('/load-more-articles',['as' => 'load-more-articles', 'uses' => 'App\Http\Controllers\PostController@loadMoreArticles']);
    Route::get('/load-more',['as' => 'load-more', 'uses' => 'App\Http\Controllers\PostController@loadMore']);
    Route::get('/load-more-cat',['as' => 'load-more-cat', 'uses' => 'App\Http\Controllers\CategoryController@loadMore']);
});

Route::get('/{any}', function(){
    return abort(404);
});

