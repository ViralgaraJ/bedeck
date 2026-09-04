@php($q = $q ?? '')
@php($activeCategory = $activeCategory ?? null)
@php($action = $action ?? null)
<section class="section tight">
    {{-- Sleek Unified Search Bar --}}
    <form class="catalog-search-bar" method="get" action="{{ $action ?? route('products.index') }}">
        <div class="search-input-wrap">
            <svg class="search-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="search" name="q" value="{{ $q }}" placeholder="Search 92 products by name, model, brand or keyword…" aria-label="Search products">
            @if($q)
                <a href="{{ $action ?? route('products.index') }}" class="search-clear" title="Clear search">&times;</a>
            @endif
        </div>
        <button class="button primary" type="submit">Search</button>
    </form>

    {{-- Category Pills --}}
    <div class="chip-row">
        <a class="chip {{ $activeCategory ? '' : 'is-active' }}" href="{{ route('products.index', array_filter(['q' => $q])) }}">
            All Products <span class="n">{{ $categories->sum('products_count') }}</span>
        </a>
        @foreach($categories as $category)
            <a class="chip {{ $activeCategory && $activeCategory->id === $category->id ? 'is-active' : '' }}"
               href="{{ route('products.category', array_filter(['category' => $category->slug, 'q' => $q])) }}">
                {{ $category->name }} <span class="n">{{ $category->products_count }}</span>
            </a>
        @endforeach
    </div>

    {{-- Results Summary Bar --}}
    <div class="catalog-summary-bar">
        <p class="catalog-count">
            <strong>{{ $products->total() }}</strong> {{ \Illuminate\Support\Str::plural('product', $products->total()) }} available
            @if($activeCategory) in <span>{{ $activeCategory->name }}</span> @endif
            @if($q) matching “<strong>{{ $q }}</strong>” @endif
        </p>
        @if($activeCategory || $q)
            <a href="{{ route('products.index') }}" class="reset-filter-link">Reset filters &times;</a>
        @endif
    </div>

    @if($products->count())
        <div class="product-grid">
            @foreach($products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
        <div class="pagination-wrap">{{ $products->links('vendor.pagination.default') }}</div>
    @else
        <div class="empty-state">
            <p><strong>No products found matching your search.</strong></p>
            <p>Try searching for a broader term like <em>“meter”</em>, <em>“valve”</em>, or <em>“OMC”</em>, or <a href="{{ route('products.index') }}">view all categories</a>.</p>
        </div>
    @endif
</section>
