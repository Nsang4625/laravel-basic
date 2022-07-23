<input type="text" name="title" value="{{ old('title', optional($post ?? null)->title) }}"><br>
@error('title')
    <div>{{ $message }}</div>
@enderror
<textarea name="content">{{ old('content', optional($post ?? null)->content) }}</textarea><br>
@if ($errors -> any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>        
@endif