<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $fillable = ['user_id', 'content'];
    public function scopeLatest(Builder $query){
        return $query->orderBy(static::CREATED_AT, 'desc');
    }
    public function commentable(){
        return $this->morphTo();
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public static function boot(){
        parent::boot();
        static::creating(function(Comment $comment){
            if($comment->commentable_type === BlogPost::class){
                Cache::tags(['blog-post'])->forget("blog-post-{$comment->blog_post_id}");
            } 
        });
    }
}
