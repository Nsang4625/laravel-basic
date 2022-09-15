<?php

namespace App\Listeners;
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Cache\Events\CacheMissed;
use Illuminate\Support\Facades\Log;

class CacheSubcriber
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handleCacheHit(CacheHit $event)
    {
        Log::info("{$event->key} cache hit");
    }
    public function handleCacheMissed(CacheMissed $event){
        Log::info("{$event->key} cache missed");
    }
    public function subscribe($events){
        $events->listen(CacheHit::class, [$this, 'handleCacheHit']);
        $events->listen(CacheMissed::class, [$this, 'handleCacheMissed']);
    }
}
