<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;// this will recreate the database by running all migration on a single 
    // test run
 
    public function test_example()
    {
        $this->assertTrue(true);
    }
    public function testNoBlogPostWhenNothingInDatabase(){
        $response = $this->get('/posts');
        $response->assertSeeText('There is no post');
    }
    public function testThereIsABlogPost(){
        // arrange
        $post = $this->createDummyBlogPost();
        // act:
        $response = $this->get('/posts');
        $response->assertSeeText('New post');
    }
    private function createDummyBlogPost():BlogPost{
        $post = new BlogPost();
        $post->title = "New post";
        $post->content = "Testing database";
        $post->save();
        return $post;
    }
    public function testSeeABlogPostWith0Comment(){
        $post = $this -> createDummyBlogPost();
        $response = $this->get('/posts');
    }
}