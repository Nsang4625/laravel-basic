<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\PostTagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
/*
Route::get('/', function () {
    return view('home.index', []);
}) -> name('home.index');

Route::get('/contact', function(){
    return view('home.contact');// view's name is alike source to blade file
}) -> name('home.contact');// should named like view
*/
// if you don't need to pass data or parameter, you can use view method like:
// Route::view('/', 'home.index')
// ->name('home.index');
// Route::view('/contact', 'home.contact')
// ->name('home.contact');
Route::get('/', [HomeController::class, 'home'])
    ->name('home.index');
Route::get('/contact', [HomeController::class, 'contact'])
    ->name('home.contact');
Route::get('/single', AboutController::class);
// with single action controller like above
//we don't need to pass the name function
Auth::routes();

// to use $posts below, we must spend 'use' statement
/*
Route::get('/posts', function() use($posts){
    request()->all();// method get all data in input and return an array 
    request()->input('post', 1);// get value of 'post' in input, default is 1
    return view('posts.index', ['posts' => $posts]);
    // variable of $posts will be assigned to the key 'posts'
    // it's like a multidimension arr
});
Route::get('/post/{id}', function($id) use($posts){
    abort_if(!isset($posts['id']), 404);
    return view('posts.show', ['post' => $posts['id']]);// gán $posts['id] cho post
    // post được chuyển sang blade trở thành array 
}) -> name('posts.show');
*/
// add constraint for required parameter
// -> where([
//     'id' => '[0-9]+'
// ])
// define constraint for id globaly in function boot inside file App/provider/RouteServiceProvider.php
// compulsory parameter
Route::resource('posts', PostsController::class);
//->only(['index', 'show', 'store', 'create', 'update', 'edit']);//give only use these methods
//in another way except() method will disable those methods
Route::get('/recent-post/{days_Ago?}', function ($daysAgo = 20) {
    return 'Posts from ' . $daysAgo . ' days ago';
})->name('posts.recent.index');
// optional parameter
/*
Route::get('/fun/response', function () use ($posts) {
    return response($posts, 201) // response obj also has view method 
        //that return blade: ->view(...)
        ->header('Content-type', 'application/json')
        ->cookie('MY_COOKIE', 'Sang', 3600);
});*/
// we can group routes which have the same prefix name
/*
Route::prefix('/fun')->name('fun.')->group(function () use ($posts) {
    Route::get('/redirect', function () {
        return redirect('/contact');
    });
    Route::get('/back', function () {
        return back(); // redirect to the address right before
    });
    Route::get('/named-route', function () {
        return redirect()->route('home.index'); // redirect to a route name
    });
    Route::get('/google', function () {
        return redirect()->away('https://google.com'); // redirect to another web page
    });
    Route::get('/jason', function () use ($posts) {
        return response()->json($posts);
    });
    Route::get('/download', function () {
        return response()->download(public_path('/Quynh.jpg'), 'hotgirl.jpg');
    });
});
*/
Route::get('/secret', [HomeController::class, 'secret'])
    -> name('secret') ->middleware('can:home.secret');
Route::get('/posts/tag/{tag}', [PostTagController::class, 'index'])
    -> name('posts.tags.index');