<?php

namespace App\Providers;

use App\Events\BlogPostPosted;
use App\Events\CommentPosted;
use App\Listeners\CacheSubcriber;
use App\Listeners\NotifyAdminWhenBlogPostCreated;
use App\Listeners\NotifyUsersAboutComment;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Observers\BlogPostObserver;
use App\Observers\CommentObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CommentPosted::class => [
            NotifyUsersAboutComment::class
        ],
        BlogPostPosted::class => [
            NotifyAdminWhenBlogPostCreated::class
        ],
    ];
    protected $subscirber = [
        CacheSubcriber::class
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        BlogPost::observe(BlogPostObserver::class);
        Comment::observe(CommentObserver::class);
    }
}
