@extends('layouts.app')
@section('title', 'Create')
@section('content')
<form action="{{ route('posts.store') }}" method="POST">
    @csrf
    @include('posts.partial.form')
    <div><input type="submit" value="Create"></div>
</form>
@endsection