<h3>
    <a href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
</h3>

<div class="mb-3">
    @if ($post->comments_count)
        <p>{{ $post->comments_count }} comments </p>
    @else
        <p>No comments</p>
    @endif
    <table>
        <tr>
            <th>
                <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">Edit</a>
            </th>
            <th>
                <form action="{{ route('posts.destroy', ['post' => $post->id]) }}">
                    @method('DELETE')
                    @csrf
                    <input type="submit" value="DELETE!" class="btn btn-primary">
            </th>
            </form>
        </tr>
    </table>
</div>
