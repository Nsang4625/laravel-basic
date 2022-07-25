@extends('layouts.app')

@section('title', $post->title)

{{-- @if ($post['is_new'])
<h1>This is the newest post</h1>
@else
<h1>An old post</h1>    
@endif

@unless ($post['is_new'])
    <div>This post is new... but using unless </div>
    @endunless
@endunless

@isset($post['has_comment'])
    <div>This post has some comments...</div>
@endisset --}}

@section('content')
<h1>{{ $post->title }}</h1>
<p>{{ $post->content }}</p>
@endsection

