<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreComment;
use App\Models\User;
use Illuminate\Http\Request;

class UserCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }
    public function store(StoreComment $request, User $user)
    {
        $user->commentsOn()->create([
            'user_id' => $request->user()->id,
            'content' => $request->input('content')
        ]);// two other fields will be filled automatically
        return redirect()->back();
    }
}
