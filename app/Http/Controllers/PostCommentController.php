<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Mail\CommentPosted;
use App\Mail\CommentPostedmd;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }
    public function store(StoreComment $request, BlogPost $post)
    {
        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $request->input('content')
        ]);
        // Mail::to($post->user)->send(
        //     new CommentPostedmd($comment)
        // );
        Mail::to($post->user)->queue(
            new CommentPostedmd($comment)
        );
        // we can use code below to specify execution delay
        // $when = now()->addMinute();
        // Mail::to($post->user)->later(
        //     $when,
        //     new CommentPostedmd($comment)
        // );
        return redirect()->back();
    }
}
