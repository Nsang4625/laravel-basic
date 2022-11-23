<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticable;
use Illuminate\Support\Facades\Bus;

class User extends Authenticable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email',
        'is_admin',
        'created_at',
        'updated_at',
        'email_verified_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function blogPosts(){
        return $this -> hasMany('App\Models\BlogPost');
    }
    public function comments(){
        return $this ->hasMany(Comment::class);
    }
    public function commentsOn(){
        return $this->morphMany('App\Models\Comment', 'commentable')->latest();
    }
    public function image(){
        return $this -> morphOne('App\Models\Image', 'imageable');
    }

    public function scopeWithMostBlogPost(Builder $query){
        return $query -> withCount('blogPosts') 
        -> orderBy('blog_posts_count', 'desc');
    }

    public function scopeWithMostBlogPostLastMonth(Builder $query){
        return $query -> withCount(['blogPosts' => function(Builder $query){
            $query -> whereBetween('created_at', [now()->subMonth(), now()]);
        }]) //-> where('blog_posts_count', '>', 2) 
        // can't using this statement because blog_posts_count is fetched as an alias, not a 
        // real column of users table so we have to use 'has' statement
        -> has('blogPosts', '>=', 2)
        -> orderBy('blog_posts_count', 'desc');
    }
    public function scopeThatHasCommentedOnPost(Builder $query, BlogPost $blogPost){// here we don't need to use BlogPost because we're in the same namespace
        return $query->whereHas('comments', function($query) use($blogPost){
            return $query->where('commentable_type' , '=', BlogPost::class)
                    ->where('commentable_id', '=', $blogPost->id);
        });
    }
    public function scopeThatIsAnAdmin(Builder $query){
        return $query->where('is_admin','=', true);
    }
}
