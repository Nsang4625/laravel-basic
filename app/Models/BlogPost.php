<?php

namespace App\Models;

use App\Scopes\DeletedAdminScope;
use App\Scopes\LatesScope;
use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use HasFactory;
    use SoftDeletes, Taggable;
    protected $fillable = ['title', 'content', 'user_id'];
    public function comments(){
        return $this->morphMany('App\Models\Comment', 'commentable')->latest();
    }
    public function image(){
        return $this->morphOne('App\Models\Image', 'imageable');
    }
    public function user(){
        return $this -> belongsTo('App\Models\User');
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
    }
}
        // add scope before boot because boot() will find and use SoftDeletes