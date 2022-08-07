<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Database\Factories\UserFactory;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        DB::table('users')->insert([
            'name' => 'Shiroyasha',
            'email' => 'gintoki@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
        $doe = User::factory()->count(5)->create();
        $else = User::factory()->count(3)->suspend()->create();
        // both lines uppon return collections
        $users = $else->concat([$doe]);// concat function append a collection(array) to another collection's end
        $posts = BlogPost::factory()->count(50)//->create(); can't create because blogpost needs user id
        ->make()->each(function($post) use ($users){
            $post -> user_id = $users->random()->id;
            $post -> save();
        });
        $comments = Comment::factory()->count(100)
        ->make()->each( function($comment) use ($posts)
        {
            # code...
            $comment -> blog_post_id = $posts -> random() -> id;
            $comment -> save();
        });
    }
}
