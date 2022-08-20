<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }
    public function store(StoreComment $request, BlogPost $post)
    {
        $post->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $request->input('content')
        ]);
        return redirect()->back();
    }
}
