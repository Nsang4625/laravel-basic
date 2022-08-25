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
    <div class="row">
        <div class="col-8">
            <h1>
                {{ $post->title }}
                @component('components.badge', ['type' => 'primary', 'show' => now()->diffInMinutes($post->created_at) < 5])
                    New post!
                @endcomponent
            </h1>
            <p>{{ $post->content }}</p>
            {{-- <img src="{{ Storage::url($post->image->path) }}"> the first way --}}
            <img src="{{ $post->image->path }}">
            @component('components.updated', ['date' => $post->created_at,
             'name' => $post->user->name])
            @endcomponent
            <p>
                Currently read by {{ $counter }} people
            </p>

            @component('components.tags', ['tags' => $post->tags])
            @endcomponent

            <h4>Comments</h4>
            @include('comments._form')
            @forelse ($post->comments as $comment)
                <p>{{ $comment->content }}</p>
                <p class="text-muted">
                    @component('components.updated', ['date' => $comment->created_at,
                    'name' => $comment->user->name])
                    @endcomponent
                </p>
            @empty
                <h5>No comments yet!</h5>
            @endforelse
        </div>
        <div class="col-4">
            @include('posts.partial.activity')
        </div>
    </div>
@endsection
