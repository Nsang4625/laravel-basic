@extends('layouts.app')

@section('title','Blog')
@section('content')
{{-- @foreach ($posts as $key => $post)
    <div>{{ $key }}.{{ $post['content'] }}</div>
@endforeach --}}
@forelse ($posts as $key => $post)
    {{-- @break($key == 1) --}}
    @include('posts.partial.post')
@empty {{-- this is a combine of foreach and empty(array) --}}
    <div>There is no post</div>
@endforelse
{{-- @each($source,$data ,$variable ) ~ @forelse + @include
    variable is which data converted to --}}
@endsection