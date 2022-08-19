<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Comment extends Model
{
    use SoftDeletes;
    use HasFactory;
    public function scopeLatest(Builder $query){
        return $query->orderBy(static::CREATED_AT, 'desc');
    }
    public function blogPost(){
        return $this->belongsTo('App\Models\BlogPost');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
