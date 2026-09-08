@csrf
@if(isset($method))@method($method)@endif

@if($errors->any())
    <div class="alert error">
        <ul style="margin:0;padding-left:1.1rem">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="form-grid">
        <label class="field full">Product name
            <input name="name" value="{{ old('name', $product->name) }}" required maxlength="180">
        </label>

        <label class="field">URL slug <span class="hint">Leave blank to auto-generate from the name.</span>
            <input name="slug" value="{{ old('slug', $product->slug) }}" maxlength="200" pattern="[A-Za-z0-9\-_]*">
        </label>

        <label class="field">Category
            <select name="category_id">
                <option value="">— Uncategorised —</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" @selected(old('category_id', $product->category_id) == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </label>

        <label class="field">Brand
            <input name="brand" value="{{ old('brand', $product->brand) }}" maxlength="120" list="brand-list">
            <datalist id="brand-list">
                @foreach(['ISOIL','E&H','Fluidwell','Hytek','PROTECH','ACCORD','Excel Instruments','UFLOW','OMC','General','Bedeck'] as $b)
                    <option value="{{ $b }}">
                @endforeach
            </datalist>
        </label>

        <label class="field">Sort order
            <input type="number" name="sort_order" value="{{ old('sort_order', $product->sort_order) }}" min="0" max="99999">
        </label>

        <label class="field full">Short description <span class="hint">One line shown on cards and search results (max 255).</span>
            <input name="short_description" value="{{ old('short_description', $product->short_description) }}" maxlength="255">
        </label>

        <label class="field full">Full description
            <textarea name="description" maxlength="8000">{{ old('description', $product->description) }}</textarea>
        </label>

        <label class="field full">Keywords <span class="hint">Comma or space separated — improves on-site search.</span>
            <input name="keywords" value="{{ old('keywords', $product->keywords) }}" maxlength="500">
        </label>

        <div class="field">
            <label class="check"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $product->is_active))> Visible on site</label>
            <label class="check" style="margin-top:.5rem"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $product->is_featured))> Featured (homepage)</label>
        </div>
    </div>
</div>

<div class="card">
    <h2 style="margin-top:0">Product photo</h2>
    <p style="color:#64727a;margin-top:0">
        <strong>Smart Auto-Processing:</strong> JPG, PNG, WEBP or GIF · max 12 MB.<br>
        Upload any photo size, shape, or orientation. The system automatically centers, fits/crops, and converts your image into optimized <code>1200×1200</code> and <code>600×600</code> WEBP files.
    </p>
    <div class="img-drop">
        <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/gif" data-image-input {{ isset($method) ? '' : 'required' }}>
        @if($product->image)
            <input type="hidden" name="keep_image" value="1">
            <img class="preview on" src="{{ $product->image_url }}" alt="current image" data-image-preview>
            <div class="dim-note">Current image shown. Choose a file only to replace it.</div>
        @else
            <img class="preview" alt="" data-image-preview>
        @endif
        <div class="dim-note" data-dim-note></div>
    </div>
</div>

<div class="card">
    <h2 style="margin-top:0">Datasheet (optional)</h2>
    <p style="color:#64727a;margin-top:0">PDF only, max 10 MB.</p>
    <input type="file" name="datasheet" accept="application/pdf">
    @if($product->datasheet)
        <p style="margin-bottom:0"><a href="{{ $product->datasheet_url }}" target="_blank" rel="noopener">Current datasheet ↗</a></p>
    @endif
</div>

<div style="display:flex;gap:.7rem">
    <button class="btn primary" type="submit">{{ $submitLabel ?? 'Save product' }}</button>
    <a class="btn" href="{{ route('admin.products.index') }}">Cancel</a>
</div>
