<div class="form-group">
    <label for="title">Title</label>
    <input type="text" id ="title" name="title" 
    value="{{ old('title', optional($post ?? null)->title) }}" class="form-control">
</div>
{{-- @error('title')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror --}}
<div class="form-group">
    <label for="content">Content</label>
    <textarea name="content" class="form-control" id="content">{{ old('content', optional($post ?? null)->content) }}</textarea>
</div>
<div class="form-group">
    <label>Thumbnail</label>
    <input type="file" name="thumbnal" class="form-control-file">
</div>
@component('components.error')
@endcomponent