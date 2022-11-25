<?php

namespace App\Http\Controllers;

use App\Events\CommentPosted;
use App\Http\Requests\StoreComment;
use App\Models\BlogPost;
use App\Http\Resources\Comment as CommentResource;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }
    public function index(BlogPost $post){
        return CommentResource::collection($post->comments()->with('user')->get());
        // sent data will be assigned to 'data' field
        // return $post->comments()->with('user')->get();// return json
    }
    public function store(StoreComment $request, BlogPost $post)
    {
        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $request->input('content')
        ]);
        event(new CommentPosted($comment));
        // Mail::to($post->user)->send(
        //     new CommentPostedmd($comment)
        // );
        // Mail::to($post->user)->queue(
        //     new CommentPostedmd($comment)
        // );
        
        // we can use code below to specify execution delay
        // $when = now()->addMinute();
        // Mail::to($post->user)->later(
        //     $when,
        //     new CommentPostedmd($comment)
        // );
        return redirect()->back();
    }
}
