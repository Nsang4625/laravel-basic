@if ($loop->even){
    <h1>This is even post</h1>
    <div>{{ $key }}.{{ $post['title'] }}</div>
}
@else
    <div style="background-color: rgb(124, 60, 60)">
    {{ $key }}.{{ $post['title'] }}
    </div>
@endif