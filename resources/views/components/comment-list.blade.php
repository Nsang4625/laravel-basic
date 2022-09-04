@forelse ($comments as $comment)
    <p>{{ $comment->content }}</p>
    <p class="text-muted">
        @component('components.updated', ['date' => $comment->created_at, 'name' => $comment->user->name,
                        'userId' => $comment->user->id])
        @endcomponent
        @component('components.tags', ['tags' => $comment->tags])
        @endcomponent
    </p>
@empty
    <h5>No comments yet!</h5>
@endforelse
