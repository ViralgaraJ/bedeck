@csrf
@if(isset($method)) @method($method) @endif

@if($errors->any())
    <div class="alert error">
        <ul style="margin:0;padding-left:1.1rem">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="form-grid">
        <label class="field full">Partner / Company Name
            <input name="name" value="{{ old('name', $partner->name) }}" required maxlength="150" placeholder="e.g. ISOIL Impianti S.p.A.">
        </label>

        <label class="field">Country
            <input name="country" value="{{ old('country', $partner->country) }}" maxlength="100" placeholder="e.g. Italy">
        </label>

        <label class="field">Website URL
            <input type="url" name="website" value="{{ old('website', $partner->website) }}" maxlength="255" placeholder="https://example.com">
        </label>

        <label class="field">Sort order <span class="hint">Lower numbers appear first</span>
            <input type="number" name="sort_order" value="{{ old('sort_order', $partner->sort_order) }}" min="0" max="9999">
        </label>

        <label class="check" style="align-self:center">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $partner->is_active))> Active (visible on live site)
        </label>
    </div>
</div>

<div class="card">
    <h2 style="margin-top:0">Partner Logo</h2>
    <p style="color:var(--muted);margin-top:0">
        Accepted formats: JPG, PNG, WEBP, SVG · Max size: 2 MB.<br>
        Recommended: Transparent PNG or SVG with clear brand logo.
    </p>

    <div class="img-drop">
        <input type="file" name="logo_file" accept="image/jpeg,image/png,image/webp,image/svg+xml" data-image-input>
        @if($partner->logo)
            <img class="preview on" src="{{ asset($partner->logo) }}" alt="Current logo" data-image-preview>
            <div class="dim-note">Current logo shown above. Upload a new file only to replace it.</div>
        @else
            <img class="preview" alt="" data-image-preview>
        @endif
        <div class="dim-note" data-dim-note></div>
    </div>
</div>

<div style="display:flex;gap:.7rem;margin-top:1rem">
    <button class="btn primary" type="submit">{{ $submitLabel ?? 'Save partner' }}</button>
    <a class="btn" href="{{ route('admin.partners.index') }}">Cancel</a>
</div>
