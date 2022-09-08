<?php

namespace App\Mail;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

// if we don't config subject, so by default it will be
// Comment Posted, separated from the name of the class
class CommentPosted extends Mailable
{
    use Queueable, SerializesModels;
    public $comment;// this will be passed automatically to the view
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
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
        // ->attach(
        //     storage_path('app/public').$this->comment->user->image->path,
        //     [
        //         'as' => 'profile_picture.jpg',
        //         'mime' => 'image/jpeg'
        //     ]
        // )
        // ->attachFromStorage($this->comment->user->image->path, 'profile_picture.jpg')
        //->attachFromStorageDisk('public', $this->comment->user->image->path)
        ->attachData(Storage::get($this->comment->user->image->path), 'profile_picture.jpeg', [
            'mime' => 'image/jpeg'
        ])
        ->subject($subject)
        ->view('emails.posts.commented');
    }
}
