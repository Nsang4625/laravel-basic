<h3>
    @if ($post->trashed())
        <del></del>
    @endif
    <a class="{{ $post->trashed() ? 'text-muted' : '' }}" href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
    @if ($post->trashed())
        </del>
    @endif
</h3>
<p class="text-muted">
    Added {{ $post->created_at->diffForHumans() }}
    by {{ $post->user->name }}
</p>
{{-- @updated(['date' => $post->created_at, 'name' => $post->user->name] )
@endupdated

@updated(['date' => $post->updated_at])
@endupdated --}}
<div class="mb-3">
    @if ($post->comments_count)
        <p>{{ $post->comments_count }} comments </p>
    @else
        <p>No comments</p>
    @endif
    @can(['delete', 'update'], $post)
        <table>
            <tr>
                <th>
                    <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">Edit</a>
                </th>
                <th>
                    <form action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST" class="form-inline">
                        @method('DELETE')
                        @csrf
                        <input type="submit" value="DELETE!" class="btn btn-primary">
                    </form>
                </th>
            </tr>
        </table>
    @endcan
</div>
