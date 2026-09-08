<a class="product-card" href="{{ route('products.show', $product) }}" data-reveal>
    <div class="product-media">
        <img src="{{ $product->thumb_url }}" alt="{{ $product->name }}" loading="lazy" width="600" height="600">
    </div>
    <div class="product-content">
        <span class="k">{{ $product->brand ?: 'Bedeck' }} @if($product->category) · {{ $product->category->name }} @endif</span>
        <strong>{{ $product->name }}</strong>
        <div class="product-action">
            <span>See More</span>
            <svg class="action-arrow" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </div>
    </div>
</a>
