@extends('layouts.app')

@section('title', 'Blog')
@section('content')
    {{-- @foreach ($posts as $key => $post)
    <div>{{ $key }}.{{ $post['content'] }}</div>
@endforeach --}}
    <div class="row">
        <div class="col-8">
            @forelse ($posts as $key => $post)
                {{-- @break($key == 1) --}}
                @include('posts.partial.post')
            @empty {{-- this is a combine of foreach and empty(array) --}}
                <div>There is no post</div>
            @endforelse
        </div>
        <div class="col-4">
            @include('posts.partial.activity')
        </div>
    </div>
    {{-- @each($source,$data ,$variable ) ~ @forelse + @include
    variable is which data converted to --}}
@endsection
