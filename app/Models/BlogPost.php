<?php

namespace App\Models;

use App\Scopes\DeletedAdminScope;
use App\Scopes\LatesScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class BlogPost extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['title', 'content', 'user_id'];
    public function comments(){
        return $this->hasMany('App\Models\Comment')->latest();
    }
    public function image(){
        return $this->hasOne(Image::class);
    }
    public function user(){
        return $this -> belongsTo('App\Models\User');
    }
    public function tags(){
        return $this -> belongsToMany('App\Models\Tag')
            ->withTimestamps();
    }

    public function scopeLatest(Builder $query){
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeMostCommented(Builder $query){
        return $query -> withCount('comments')// create a field comments_count as an alias 
        // it's not a real column of blogposts table
        -> orderBy('comments_count', 'desc');
    }

    public static function boot(){

        static::addGlobalScope(new DeletedAdminScope);
        parent::boot();
        // static::addGlobalScope(new LatesScope);

        static::deleting(function (BlogPost $blogPost){
            $blogPost -> comments() -> delete();
            Cache::tags(['blog-post'])->forget("blog-post-{$blogPost -> id}");
        });
        static::restoring(function (BlogPost $blogPost){
            $blogPost -> comments() -> restore();
        });
        static::updating(function(BlogPost $blogPost){
            Cache::tags(['blog-post'])->forget("blog-post-{$blogPost -> id}");
        });
    }
}
        // add scope before boot because boot() will find and use SoftDeletes