@extends('layouts.app')
@section('title', 'Update')
@section('content')
<form action="{{ route('posts.update', ['post' => $post -> id]) }}" method="POST">
    @csrf
    @method('PUT')
    @include('posts.partial.form')
    <div><input type="submit" value="Update"></div>
</form>
@endsection