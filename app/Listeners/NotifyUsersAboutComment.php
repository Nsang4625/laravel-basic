<?php

namespace App\Listeners;

use App\Events\CommentPosted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Jobs\NotifyUsersPostWasCommented;
use App\Jobs\ThrottledMail;
use App\Mail\CommentPostedmd;

class NotifyUsersAboutComment
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
    public function handle(CommentPosted $event)
    {
        ThrottledMail::dispatch(new CommentPostedmd($event->comment),
         $event->comment->commentable->user)
        ->onQueue('high');
        NotifyUsersPostWasCommented::dispatch($event->comment)
        ->onQueue('low');
    }
}
