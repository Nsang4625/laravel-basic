<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <title>Laravel app - @yield('title')</title>
</head>

<body>
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
        <h5 class="my-0 mr-md-auto font-weight-normal">Laravel App</h5>
        <nav class="my-2 my-md-0 mr-md-3">
            <a class="p-2 text-dark" href="{{ route('home.index') }}">Home</a>
            <a class="p-2 text-dark" href="{{ route('home.contact') }}">Contact</a>
            <a class="p-2 text-dark" href="{{ route('posts.index') }}">Blog Posts</a>
            <a class="p-2 text-dark" href="{{ route('posts.create') }}">Add blog</a>
            @guest
                <a class="p-2 text-dark" href="{{ route('register') }}">Register</a>
                <a class="p-2 text-dark" href="{{ route('login') }}">Log in</a>
            @else
                <a class="p-2 text-dark" href="{{ route('logout') }}"
                    onclick="event.preventDefault();document.getElementById('logout-form').submit()">
                    Log out({{ Auth::user()->name }})
                </a>
                <form action="{{ route('logout') }}" id="logout-form" method="POST" style="display: none;">
                    @csrf
                </form>
            @endguest
        </nav>
    </div>
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div>
            @yield('content')
        </div>
    </div>
    <script src="{{ mix('js/app.js') }}" defer></script>
</body>

</html>
