<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class Counter{
    public function increment(string $key, array $tags = null):int {
        $sessionId = session()->getId(); // get user's session
        $counterKey = "{$key}-counter";
        // count users on page
        $usersKey = "{$key}-users";
        // fetch and store data about users that visited the page 
        $users = Cache::get($usersKey, []);
        $userUpdate = [];
        $difference = 0;
        $now = now();
        foreach ($users as $session => $lastVisited) {
            if ($now->diffInMinutes($lastVisited) >= 1) {
                $difference--;
            } else {
                $userUpdate[$session] = $lastVisited;
            }
        }

        if (
            !array_key_exists($sessionId, $users)
            || $now->diffInMinutes($users[$sessionId]) >= 1
        ) {
            $difference++;
        }
        $userUpdate[$sessionId] = $now;
        Cache::tags(['blog-post'])->forever($usersKey, $userUpdate);
        if (!Cache::tags(['blog-post'])->has($counterKey)) {
            Cache::tags(['blog-post'])->forever($counterKey, 1);
        } else {
            Cache::tags(['blog-post'])->increment($counterKey, $difference);
        }
        $counter = Cache::tags(['blog-post'])->get($counterKey);
        return $counter;
    }
}