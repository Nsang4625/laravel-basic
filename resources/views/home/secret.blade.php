@extends('layouts.app')

@section('title', 'Secret Page')
@section('content')
<h1>Contact me here: 0818623567</h1>
@can('home.secret')
    <p>Special contact: nsang@gmail.com</p>
@endcan
@endsection