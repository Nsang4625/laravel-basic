<?php

namespace App\Http\Controllers;

use Illuminate\Http\File;
use App\Http\Requests\StorePost;
use App\Models\BlogPost;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Access\Gate;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

// laravel automatically converts these method to ability of model's policy
// [
//     'show' => 'view',
//     'create' => 'create',
//     'store' => 'create',
//     'edit' => 'update',
//     'update' => 'update',
//     'destroy' => 'delete',
// ]

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'update', 'edit', 'store', 'destroy']);
        // make the middleware execute only for some functions
    }
    // public $posts = [
    //     1 => [
    //         'title' => 'Intro to laravel',
    //         'content' => 'Welcome to laravel 8',
    //         'is_new' => true,
    //         'has_comment' => true
    //     ],
    //     2 => [
    //         'title' => 'Intro to php',
    //         'content' => 'Welcome to php 8.1.7',
    //         'is_new' => false
    //     ]
    // ];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //DB::connection()->enableQueryLog();// this will enable logging of all
        // queries that are made inside laravel
        // $mostCommented = Cache::remember('mostCommented', now()->addMinutes(5), function () {
        //     return BlogPost::mostCommented()->take(3)->get();
        // });
        // $mostActive = Cache::remember('mostActive', now()->addMinutes(5), function () {
        //     return User::withMostBlogPost()->take(3)->get();
        // });
        // $mostActiveLastMonth = Cache::remember('mostActiveLastMonth', now()->addMinutes(5), function () {
        //     return User::withMostBlogPostLastMonth()->take(3)->get();
        // });

        return view(
            'posts.index',
            [
                'posts' => BlogPost::latest()->withCount('comments')->with('user')
                ->with('tags')->get()
                // 'most_commented'  => BlogPost::mostCommented()->take(3)->get(),//$mostCommented,
                // 'most_active' => User::withMostBlogPost()->take(3)->get(),//$mostActive,
                // 'most_active_last_month' =>  User::withMostBlogPostLastMonth()->take(3)->get(),//$mostActiveLastMonth
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePost $request)
    {
        // $request->validate(
        //     [
        //         'title' => 'required|min:5|max:10',
        //         'content' => 'required'
        //     ]
        // );
        // $post = new BlogPost();
        // $post->title = $request->input('title');
        // $post->content = $request->input('content');
        // $post->save();
        // return redirect()->route('posts.show', ['post' => $post->id]);
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        
        $post = new BlogPost();
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->user_id = $validated['user_id'];
        $post->save();

        
        if($request->hasFile('thumbnail')){
            $path = $request->file('thumbnail')->store('photo');
            $post->image()->save(
                Image::create(['path' => $path])
            );
            // $file->store('photo');//inside the brackets is folder name for that img
            // Storage::putFile('photo', $file);
            // $file->storeAs('photo', $post->id . '.' . $file->guessExtension());
            // Storage::putFileAs('photo', $file, $post->id . '.' . $file->guessExtension());
        }
        /*
        $post = BlogPost::create($validated)
        we can use this instead of 5 lines up here
        */




        $request->session()->flash('status', 'Blog post was created!');
        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // abort_if(!isset($posts['id']), 404);
        // return view('posts.show',
        //  ['post' => BlogPost::with(['comments' => function($query){
        //     return $query->latest();
        //  }])
        //  ->findOrFail($id)]);
        $blogPost = Cache::tags(['blog-post'])->remember("blog-post-{$id}", 60, function () use ($id) {
            return BlogPost::with('comments')
            ->with('tags')
            ->with('comments.user')// fetch another related model of previous related model 
            ->with('user')->findOrFail($id);
        });
        $sessionId = session()->getId(); // get user's session
        $counterKey = "blog-post-{$id}-counter";
        // count users on page
        $usersKey = "blog-post-{$id}-users";
        // fetch and store data about users that visited the page 
        $users = Cache::get($usersKey, []);
        $userUpdate = [];
        $difference = 0;
        $now = now();
        foreach ($users as $session => $lastVisited) {
            if ($now->diffInMinutes($lastVisited) >= 1) {
                $difference--;
            } else {
                $userUpdate[$session] = $lastVisited;
            }
        }

        if (
            !array_key_exists($sessionId, $users)
            || $now->diffInMinutes($users[$sessionId]) >= 1
        ) {
            $difference++;
        }
        $userUpdate[$sessionId] = $now;
        Cache::tags(['blog-post'])->forever($usersKey, $userUpdate);
        if (!Cache::tags(['blog-post'])->has($counterKey)) {
            Cache::tags(['blog-post'])->forever($counterKey, 1);
        } else {
            Cache::tags(['blog-post'])->increment($counterKey, $difference);
        }
        $counter = Cache::tags(['blog-post'])->get($counterKey);
        return view(
            'posts.show',
            [
                'post' => $blogPost,
                'counter' => $counter
            ]
        );
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        // if(Gate::denies('update-post', $post)){// user will be passed auto by lar
        //     abort(403, 'You can not update this post');
        // }
        $this->authorize('update', $post);
        // you can even remove 'update' because laravel will convert edit() to update 
        // policy of the model instance that was passed
        return view('posts.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);
        // if(Gate::denies('update-post', $post)){// user will be passed auto by lar
        //     abort(403, 'You can not update this post');
        // }
        $this->authorize('update', $post);
        $validated = $request->validated();
        $post->fill($validated);
        $post->save();
        request()->session()->flash('Message', 'The post was updated succesfully');
        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = BlogPost::findOrFail($id);
        // if(Gate::denies('delete-post', $post)){// user will be passed auto by lar
        //     abort(403, 'You can not update this post');
        // }
        // $this -> authorize('posts.delete', $post);
        $this->authorize('delete', $post); // laravel will automatically use exact policy for $post
        $post->delete();
        session()->flash('status', 'Blog post was deleted');
        return redirect()->route('posts.index');
    }
}
