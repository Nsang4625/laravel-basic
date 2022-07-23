@if ($loop->even){
    <h1>This is even post</h1>
    <div>{{ $key }}.{{ $post -> title }}</div>
}
@else
    <div style="background-color: rgb(124, 60, 60)">
    {{ $key }}.{{ $post -> content }}
    </div>
@endif
<div>
    <form action="{{ route('home.contact', ['post' => $post -> id]) }}">
        @method('DELETE')
        @csrf
        <input type="submit" value="DELETE!">
    </form>
</div>