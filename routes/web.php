<?php

use Illuminate\Http\Request;
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
Route::view('/', 'home.index')
->name('home.index');
Route::view('/contact', 'home.contact')
->name('home.contact');

$posts = [
    1 => [
        'title' => 'Intro to laravel',
        'content' => 'Welcome to laravel 8',
        'is_new' => true,
        'has_comment' => true
    ],
    2 => [
        'title' => 'Intro to php',
        'content' => 'Welcome to php 8.1.7',
        'is_new' => false
    ]
];
// to use $posts below, we must spend 'use' statement

Route::get('/posts', function() use($posts){
    request()->all();// all method get all data in input and return an array 
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
// add constraint for required parameter
// -> where([
//     'id' => '[0-9]+'
// ])
// define constraint for id globaly in function boot inside file App/provider/RouteServiceProvider.php

// compulsory parameter

Route::get('/recent-post/{days_Ago?}', function($daysAgo = 20){
    return 'Posts from ' . $daysAgo . ' days ago';
}) -> name('posts.recent.index');
// optional parameter

Route::get('/fun/response',function() use($posts){
    return response($posts, 201) // this obj also has view method 
    //that return blade
    -> header('Content-type', 'application/json') 
    ->cookie('MY_COOKIE', 'Sang', 3600);
});
// we can group routes which have the same prefix name
Route::prefix('/fun')->name('fun.')->group(function()use($posts){
Route::get('/redirect', function(){
    return redirect('/contact');
 });
Route::get('/back', function(){
    return back();// redirect to the address right before
 });
Route::get('/named-route', function(){ 
    return redirect()->route('home.index');// redirect to a route name
});
Route::get('/google', function(){
    return redirect()->away('https://google.com');// redirect to another web page
});
Route::get('/jason', function() use($posts){
    return response()->json($posts);
});
Route::get('/download', function(){
    return response()->download(public_path('/Quynh.jpg'), 'hotgirl.jpg');
});
});