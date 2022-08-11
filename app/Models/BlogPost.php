<?php

namespace App\Models;

use App\Scopes\LatesScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['title', 'content', 'user_id'];
    public function comments(){
        return $this->hasMany('App\Models\Comment');
    }
    public function user(){
        return $this -> belongsTo('App\Models\User');
    }
    public static function boot(){
        parent::boot();
        static::addGlobalScope(new LatesScope);
        static::deleting(function (BlogPost $blogPost){
            $blogPost -> comments() -> delete();
        });
        static::restoring(function (BlogPost $blogPost){
            $blogPost -> comments() -> restore();
        });
    }
}
