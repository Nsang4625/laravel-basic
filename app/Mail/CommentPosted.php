<?php

namespace App\Mail;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
// if we don't config subject, so by default it will be
// Comment Posted, separated from the name of the class
class CommentPosted extends Mailable
{
    use Queueable, SerializesModels;
    public $commented;// this will be passed automatically to the view
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->commented = $comment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Comment was posted in your {$this->comment->commentable->title} blog post";
        return $this//->from('nsang4625@gmail.com', 'admin')
        // use this if you do not spend default email in mail.php 
        ->subject($subject)
        ->view('emails.posts.commented');
    }
}
