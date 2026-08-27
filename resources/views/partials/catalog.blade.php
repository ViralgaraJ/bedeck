@php($q = $q ?? '')
@php($activeCategory = $activeCategory ?? null)
@php($action = $action ?? null)
<section class="section">
    <form class="catalog-toolbar" method="get" action="{{ $action ?? route('products.index') }}" data-reveal>
        <label>Search products
            <input type="search" name="q" value="{{ $q }}" placeholder="Meter, valve, transmitter, brand…">
        </label>
        <button class="button secondary dark" type="submit">Search</button>
    </form>

    <div class="chip-row" data-reveal>
        <a class="chip {{ $activeCategory ? '' : 'is-active' }}" href="{{ route('products.index', array_filter(['q' => $q])) }}">
            All <span class="n">{{ $categories->sum('products_count') }}</span>
        </a>
        @foreach($categories as $category)
            <a class="chip {{ $activeCategory && $activeCategory->id === $category->id ? 'is-active' : '' }}"
               href="{{ route('products.category', array_filter(['category' => $category->slug, 'q' => $q])) }}">
                {{ $category->name }} <span class="n">{{ $category->products_count }}</span>
            </a>
        @endforeach
    </div>

    <p class="catalog-count">{{ $products->total() }} {{ \Illuminate\Support\Str::plural('product', $products->total()) }} found{{ $q ? ' for “'.$q.'”' : '' }}.</p>

    @if($products->count())
        <div class="product-grid">
            @foreach($products as $product)
                @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
        <div class="pagination-wrap">{{ $products->links() }}</div>
    @else
        <div class="empty-state" data-reveal>
            <p><strong>No products matched.</strong></p>
            <p>Try a different keyword or <a href="{{ route('products.index') }}">browse the full catalogue</a>.</p>
        </div>
    @endif
</section>
