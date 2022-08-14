<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticable;

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
}
