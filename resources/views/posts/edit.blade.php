@extends('layouts.app')
@section('title', 'Update')
@section('content')
<form action="{{ route('posts.update', ['post' => $post -> id]) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('posts.partial.form')
    <div><input type="submit" value="Update" class="btn btn-primary btn-block"></div>
</form>
@endsection