<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class BlogPostTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tagCount = Tag::all()->count();
        if(0 === $tagCount){
            $this->command->info('No tag for assigning');
            return;
        }
        $tags = $tagCount/2;
        BlogPost::all()->each(function(BlogPost $blogPost) use($tags){
            $tagged = Tag::inRandomOrder()->take($tags)->get()->pluck('id');
            $blogPost->tags()->sync($tagged);
        });
    }
}
