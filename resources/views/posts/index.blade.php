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
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">Most commented post</h5>
                    <p class="card-text">What people are attractive</p>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach ($most_commented as $post)
                        <li class="list-group-item">
                            <a href="{{ route('posts.show', ['post' => $post -> id]) }}">
                                {{  $post -> title }}
                            </a>    
                        </li>    
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    {{-- @each($source,$data ,$variable ) ~ @forelse + @include
    variable is which data converted to --}}
@endsection
