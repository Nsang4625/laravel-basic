<?php

namespace App\Http\Controllers;

use App\Contracts\CounterContract;
use App\Events\BlogPostPosted;
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
    private $counter;
    public function __construct(CounterContract $counter)
    {
        $this->middleware('auth')->only(['create', 'update', 'edit', 'store', 'destroy']);
        // make the middleware execute only for some functions
        $this->counter = $counter;
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
                Image::make(['path' => $path])
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
        event(new BlogPostPosted($post));



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
        // $counter = resolve(Counter::class);
        // resolve means getting an instance from the container 
        return view(
            'posts.show',
            [
                'post' => $blogPost,
                'counter' => $this->counter->increment("blog-post-{$id}",['blog-post'])
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
        if($request->hasFile('thumbnail')){
            $path = $request->file('thumbnail')->store('photo');
            if($post->image){
                Storage::delete($post->image->path);
                $post->image->path = $path;
                $post->image->save();
            }else{
            $post->image()->save(
                Image::make(['path' => $path])
            );
            }
        }
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
