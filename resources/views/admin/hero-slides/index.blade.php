@extends('layouts.admin')
@section('title', 'Carousel Images')

@section('content')
<div class="admin-head">
    <h1>Carousel Images</h1>
</div>

<p style="color:#8b98a3;margin-top:-.6rem;margin-bottom:1.6rem">
    Each page below rotates through the images shown here, in this order. Add a new image, delete one you no longer
    want, or use the arrows to reorder. Images stretch to fill the banner, so wide photos (roughly 2:1) work best.
</p>

@foreach($pageKeys as $key => $label)
    @php($pageSlides = $slides->get($key, collect()))
    <div class="card" id="{{ $key }}">
        <h2 style="margin-top:0">{{ $label }} page</h2>

        @if($pageSlides->isEmpty())
            <p class="slide-empty">No carousel images set for this page yet — add one below.</p>
        @else
            <div class="slide-gallery">
                @foreach($pageSlides as $slide)
                    <div class="slide-card">
                        <span class="slide-order">{{ $loop->iteration }}</span>
                        <img src="{{ $slide->image_url }}" alt="{{ $label }} carousel image {{ $loop->iteration }}">
                        <div class="slide-actions">
                            @unless($loop->first)
                                <form action="{{ route('admin.hero-slides.move-up', $slide) }}" method="post">
                                    @csrf
                                    <button class="btn sm" type="submit" title="Move earlier">&uarr;</button>
                                </form>
                            @endunless
                            @unless($loop->last)
                                <form action="{{ route('admin.hero-slides.move-down', $slide) }}" method="post">
                                    @csrf
                                    <button class="btn sm" type="submit" title="Move later">&darr;</button>
                                </form>
                            @endunless
                            <form action="{{ route('admin.hero-slides.destroy', $slide) }}" method="post"
                                  onsubmit="return confirm('Remove this carousel image?')" style="margin-left:auto">
                                @csrf @method('DELETE')
                                <button class="btn sm danger" type="submit">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.hero-slides.store') }}" method="post" enctype="multipart/form-data" style="display:flex;gap:.8rem;align-items:flex-end;flex-wrap:wrap">
            @csrf
            <input type="hidden" name="page_key" value="{{ $key }}">
            <label class="field" style="flex:1;min-width:220px">Add an image
                <input type="file" name="image" accept="image/jpeg,image/png,image/webp" required>
            </label>
            <button class="btn accent" type="submit">Add to {{ $label }}</button>
        </form>
        @error('image')
            @if(old('page_key') === $key)<p style="color:var(--danger);font-size:.85rem;margin:.5rem 0 0">{{ $message }}</p>@endif
        @enderror
    </div>
@endforeach
@endsection
