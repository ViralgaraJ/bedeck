@csrf
@if(isset($method))@method($method)@endif

@if($errors->any())
    <div class="alert error"><ul style="margin:0;padding-left:1.1rem">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
@endif

<div class="card">
    <div class="form-grid">
        <label class="field">Name
            <input name="name" value="{{ old('name', $category->name) }}" required maxlength="150">
        </label>
        <label class="field">Slug <span class="hint">Blank = auto from name.</span>
            <input name="slug" value="{{ old('slug', $category->slug) }}" maxlength="170" pattern="[A-Za-z0-9\-_]*">
        </label>
        <label class="field full">Description
            <textarea name="description" maxlength="1000">{{ old('description', $category->description) }}</textarea>
        </label>
        <label class="field">Sort order
            <input type="number" name="sort_order" value="{{ old('sort_order', $category->sort_order) }}" min="0" max="9999">
        </label>
        <label class="check" style="align-self:end">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active))> Active
        </label>
    </div>
</div>

<div style="display:flex;gap:.7rem">
    <button class="btn primary" type="submit">{{ $submitLabel ?? 'Save' }}</button>
    <a class="btn" href="{{ route('admin.categories.index') }}">Cancel</a>
</div>
