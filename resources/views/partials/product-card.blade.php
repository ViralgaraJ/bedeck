<a class="product-card" href="{{ route('products.show', $product) }}" data-reveal>
    <div class="product-media">
        <img src="{{ $product->thumb_url }}" alt="{{ $product->name }}" loading="lazy" width="600" height="600">
    </div>
    <div class="product-content">
        <span class="k">{{ $product->brand ?: 'Bedeck' }} @if($product->category) · {{ $product->category->name }} @endif</span>
        <strong>{{ $product->name }}</strong>
        <p>{{ \Illuminate\Support\Str::limit($product->short_description ?: $product->description, 96) }}</p>
    </div>
</a>
