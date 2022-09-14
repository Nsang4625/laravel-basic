<?php

namespace App\Listeners;

use App\Events\BlogPostPosted;
use App\Mail\BlogPostAdded;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use PhpParser\Node\Expr\Throw_;
use App\Jobs\ThrottledMail;

class NotifyAdminWhenBlogPostCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(BlogPostPosted $event)
    {
        User::thatIsAnAdmin()->get()
            ->map(function ($user) {
                ThrottledMail::dispatch(
                    new BlogPostAdded(),
                    $user
            );
            });
    }
}
