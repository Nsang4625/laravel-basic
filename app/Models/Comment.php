<?php

namespace App\Models;

use App\Traits\Taggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Comment extends Model
{
    use SoftDeletes, Taggable;
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
}
