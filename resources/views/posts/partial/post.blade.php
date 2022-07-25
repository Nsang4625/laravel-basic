
    <h3><a href="{{ route('posts.show', ['post' => $post->id]) }}" class="btn btn-primary">{{ $post -> title }}</a>
    </h3>

<div class="mb-3">
    <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">Edit</a>
    <form action="{{ route('posts.destroy', ['post' => $post -> id]) }}">
        @method('DELETE')
        @csrf
        <input type="submit" value="DELETE!" class="btn btn-primary">
    </form>
</div>