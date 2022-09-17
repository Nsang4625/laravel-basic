<?php

namespace App\Services;

use App\Contracts\CounterContract;
use Illuminate\Contracts\Cache\Factory as Cache;
use Illuminate\Contracts\Session\Session;

class Counter implements CounterContract{
    private $timeout;
    private $cache;
    private $session;
    private $supportsTags;
    public function __construct(Cache $cache, Session $session, int $timeout){
        $this->timeout = $timeout;
        $this->cache = $cache;
        $this->session = $session;
        $this->supportsTags = method_exists($this->cache, 'tags');
    }
    public function increment(string $key, array $tags = null):int {
        $sessionId = $this->session->getId(); // get user's session
        $counterKey = "{$key}-counter";
        // count users on page
        $usersKey = "{$key}-users";
        // fetch and store data about users that visited the page 
        $cache = ($this->supportsTags && $tags !== null) ? $this->cache->tags($tags) : $this->cache;
        $users = $cache->get($usersKey, []);
        $userUpdate = [];
        $difference = 0;
        $now = now();
        foreach ($users as $session => $lastVisited) {
            if ($now->diffInMinutes($lastVisited) >= $this->timeout) {
                $difference--;
            } else {
                $userUpdate[$session] = $lastVisited;
            }
        }

        if (
            !array_key_exists($sessionId, $users)
            || $now->diffInMinutes($users[$sessionId]) >= $this->timeout
        ) {
            $difference++;
        }
        $userUpdate[$sessionId] = $now;
        $cache->forever($usersKey, $userUpdate);
        if (!$cache->has($counterKey)) {
            $cache->forever($counterKey, 1);
        } else {
            $cache->increment($counterKey, $difference);
        }
        $counter = $cache->get($counterKey);
        return $counter;
    }
}