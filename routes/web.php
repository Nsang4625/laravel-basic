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

Route::get('/post/{id}', function($id){
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
