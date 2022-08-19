<div class="mb-2 mt-2">
    @auth
    <form action="{{ route('posts.comments.store', ['post'=> $post->id]) }}" method="POST">
        @csrf
        <div class="form-group">
            <textarea name="content" class="form-control" id="content">
            </textarea>
        </div>
        <div><input type="submit" value="Add comment" class="btn btn-primary btn-block">
        </div>
    </form>
    @else
    Please <a href="{{ route('login') }}">sign in</a> to comment
    @endauth    
</div>
<hr>
