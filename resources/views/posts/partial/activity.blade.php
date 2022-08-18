<div class="container">
    <div class="row">
        {{-- <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Most commented post</h5>
                <p class="card-text">What people are attractive</p>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($most_commented as $post)
                    <li class="list-group-item">
                        <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                            {{ $post->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div> --}}
        {{-- @card()
        
        @endcard() --}}
        @component('components.card', ['title' => 'Most commented post'])
            @slot('subtitle')
                What people are attractive
            @endslot
            @slot('items')
                @foreach ($most_commented as $post)
                    <li class="list-group-item">
                        <a href="{{ route('posts.show', ['post' => $post->id]) }}">
                            {{ $post->title }}
                        </a>
                    </li>
                @endforeach
            @endslot
        @endcomponent
    </div>
    <div class="row">
        {{-- <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Most active user</h5>
                <p class="card-text">User with most posts</p>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($most_active as $user)
                    <h5>{{ $user->name }}</h5>
                @endforeach
            </ul>
        </div> --}}
        @component('components.card', ['title' => 'Most active user'])
            @slot('subtitle')
                User with most posts
            @endslot
            @slot('items', collect($most_active)->pluck('name'))
        @endcomponent
    </div>
    <div class="row">
        {{-- <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Most active user last month</h5>
                <p class="card-text">User with most posts last month</p>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($most_active_last_month as $user)
                    <h5>{{ $user->name }}</h5>
                @endforeach
            </ul>
        </div> --}}
        @component('components.card',
            ['title' => 'Most active user last month', 'subtitle' => 'User with most posts last month'])
            @slot('items', collect($most_active_last_month)->pluck('name'))
        @endcomponent
    </div>
</div>