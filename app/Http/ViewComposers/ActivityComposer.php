<?php

namespace App\Http\ViewComposers;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ActivityComposer{
    public function compose(View $view){
        $mostCommented = Cache::remember('mostCommented', now()->addMinutes(5), function () {
            return BlogPost::mostCommented()->take(3)->get();
        });
        $mostActive = Cache::remember('mostActive', now()->addMinutes(5), function () {
            return User::withMostBlogPost()->take(3)->get();
        });
        $mostActiveLastMonth = Cache::remember('mostActiveLastMonth', now()->addMinutes(5), function () {
            return User::withMostBlogPostLastMonth()->take(3)->get();
        });
        $view->with('mostCommented', $mostCommented);
        $view->with('mostActive', $mostActive);
        $view->with('mostActiveLastMonth', $mostActiveLastMonth);
    }
}