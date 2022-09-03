<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\BlogPost;
use App\Models\User;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = BlogPost::all();
        $users = User::all();
        if($posts->count() === 0|| $users->count() === 0){
            $this->command->info('There is no post or user');
            return;
        }
        Comment::factory()->count(100)
        ->make()->each( function($comment) use ($posts, $users)
        {
            # code...
            $comment -> user_id = $users -> random() -> id;
            $comment -> commentable_id = $posts -> random() -> id;
            $comment -> commentable_type = BlogPost::class;
            $comment -> save();
        });
        Comment::factory()->count(20)
        ->make()->each( function($comment) use ($users)
        {
            # code...
            $comment -> user_id = $users -> random() -> id;
            $comment -> commentable_id = $users -> random() -> id;
            $comment -> commentable_type = User::class;
            $comment -> save();
        });
    }
}
