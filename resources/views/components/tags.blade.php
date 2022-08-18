<p>
    @foreach ($tags as $tag)
        <a href="#" class="badge text-bg-primary">{{ $tag->name }}</a>
    @endforeach
</p>