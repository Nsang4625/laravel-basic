<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\CommentPosted;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreComment;
use App\Http\Resources\Comment as CommentResource;
use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Http\Request;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BlogPost $post, Request $request)
    // remember that name of passed variable must match the name in route's parameter
    {
        /*return Comment::collection($post->comments()->with('user')->get());
            this will return a wrapped collection in the key 'data'
            to change that, edit in file AppServiceProvider.php */

        $perPage = $request->input('per_page') ?? 15;
        return CommentResource::collection($post->comments()->with('user')->paginate(5)
            ->appends([
                'per_page' => $perPage
            ]));
        // even we use withoutWrapping, it still returns back in data property
        // with some additional properties like links, meta
        // laravel also auto generate a parameter in the url: ?page=...
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreComment $request, BlogPost $post)
    {
        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $request->input('content')
        ]);
        event(new CommentPosted($comment));
        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogPost $post, Comment $comment, StoreComment $request)
    {
        $this->authorize($comment);
        $comment->content = $request->input('content');
        $comment->save();
        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        $this->authorize($comment);
        $comment->delete();
        return response()->noContent();
    }
}
