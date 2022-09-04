<?php

namespace App\Traits;

use App\Models\Tag;

trait Taggable{
    protected static function bootTaggable(){
        static::updating(function ($model){
            $model->tags()->sync(static::findTagsInContent($model->content));
        });
        static::created(function ($model){
            $model->tags()->sync(static::findTagsInContent($model->content));
        });
        // here we use updating because we already have an id model
        // created because after creating model we would have an id model 
    }
    public function tags(){
        return $this -> morphToMany('App\Models\Tag', 'taggable')
            ->withTimestamps();
    }
    private static function findTagsInContent($content){
        preg_match_all('/@([^@]+)@/m', $content, $tags);
        // this will create a tags array 
        return Tag::whereIn('name', $tags[1] ?? [])->get();
        // tags[0] contains everything matches
        // ex: @science@ 
        // tags[1] contains all group matches
        // ex: science
        // group is created when adding () to regex
    }
}