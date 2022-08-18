@extends('layouts.app')

@section('title', $post->title)

{{-- @if ($post['is_new'])
<h1>This is the newest post</h1>
@else
<h1>An old post</h1>    
@endif

@unless($post['is_new'])
    <div>This post is new... but using unless </div>
    @endunless
@endunless

@isset($post['has_comment'])
    <div>This post has some comments...</div>
@endisset --}}

@section('content')
    <h1>
        {{ $post->title }}
        {{-- @badge(['type' => 'primary', 'show' => now()->diffInMinutes($post->created_at) < 5])
            New post!
        @endbadge --}}
        @component('components.badge', ['type' => 'primary', 'show' => now()->diffInMinutes($post->created_at) < 5])
            New post!
        @endcomponent
    </h1>
    <p>{{ $post->content }}</p>
    {{-- <p>Added {{ $post->created_at->diffForHumans() }} </p> --}}
    {{-- @updated(['date' => $post->created_at])
    @endupdated --}}
    @component('components.updated', ['date' => $post->created_at])
    @endcomponent
    <p>
        Currently read by {{ $counter }} people
    </p>

    @component('components.tags', ['tags' => $post->tags])
    @endcomponent
     
    <h4>Comments</h4>
    @forelse ($post->comments as $comment)
        <p>{{ $comment->content }}</p>
        <p class="text-muted">
            {{-- {{ $comment->created_at->diffForHumans() }} --}}
            {{-- @updated()
            @endupdated --}}
            @component('components.updated', ['date' => $comment->created_at])
            @endcomponent
        </p>
    @empty
        <h5>No comments yet!</h5>
    @endforelse
@endsection
