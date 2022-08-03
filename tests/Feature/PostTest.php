<?php

namespace Tests\Feature;

use App\Models\BlogPost;
use App\Models\Comment;
use Illuminate\Contracts\Session\Session;
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
    // public function testThereIsABlogPost(){
    //     // arrange
    //     $post = $this->createDummyBlogPost();
    //     // act:
    //     $response = $this->get('/posts');
    //     $response->assertSeeText('New post');
    // }
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
        $response->assertSeeText('Testing database');
    }
    public function testSeeABlogPostWithComments(){
        //arrange
        $post = $this->createDummyBlogPost();
        Comment::factory()->count(4)->create([
            'blog_post_id' => $post -> id
        ]);
        $response = $this->get('/posts');
        $response->assertSeeText('4 comments');
    }
    public function testStoreValid(){

        $params = [
            'title' => 'Valid title',
            'content' => 'At least 10 characters'
        ];
        
        $this->actingAs($this->user())
        ->post('/posts', $params)
        ->assertStatus(302);
        //->assertSessionHas('status');

        // $this->assertEquals(session('status'), 'Blog post was created!');
    }
}
