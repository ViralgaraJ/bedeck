@extends('layouts.admin')
@section('title', 'Products')

@section('content')
<div class="admin-head">
    <h1>Products <span style="color:#64727a;font-size:1rem">({{ $products->total() }})</span></h1>
    <a class="btn accent" href="{{ route('admin.products.create') }}">+ Add product</a>
</div>

<form class="card" method="get" style="display:flex;gap:.8rem;flex-wrap:wrap;align-items:end">
    <label class="field" style="flex:1;min-width:220px">Search
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Name, brand, keyword…">
    </label>
    <label class="field" style="min-width:190px">Category
        <select name="category">
            <option value="">All categories</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>
            @endforeach
        </select>
    </label>
    <label class="field" style="min-width:150px">Status
        <select name="status">
            <option value="">Any</option>
            <option value="active" @selected(request('status') === 'active')>Active</option>
            <option value="inactive" @selected(request('status') === 'inactive')>Hidden</option>
        </select>
    </label>
    <button class="btn primary" type="submit">Filter</button>
    @if(request()->hasAny(['q','category','status']))<a class="btn" href="{{ route('admin.products.index') }}">Reset</a>@endif
</form>

<div class="table-wrap">
    <table>
        <thead><tr><th></th><th>Name</th><th>Category</th><th>Brand</th><th>Featured</th><th>Status</th><th>Order</th><th></th></tr></thead>
        <tbody>
        @forelse($products as $p)
            <tr>
                <td><img class="thumb" src="{{ $p->thumb_url }}" alt=""></td>
                <td><strong>{{ $p->name }}</strong><br><span style="color:#64727a;font-size:.82rem">/{{ $p->slug }}</span></td>
                <td>{{ optional($p->category)->name ?? '—' }}</td>
                <td>{{ $p->brand ?? '—' }}</td>
                <td>{{ $p->is_featured ? '★' : '—' }}</td>
                <td>{{ $p->is_active ? 'Active' : 'Hidden' }}</td>
                <td>{{ $p->sort_order }}</td>
                <td style="white-space:nowrap">
                    <a class="btn sm" href="{{ route('admin.products.edit', $p) }}">Edit</a>
                    <a class="btn sm" href="{{ route('products.show', $p) }}" target="_blank" rel="noopener">View</a>
                    <form action="{{ route('admin.products.destroy', $p) }}" method="post" style="display:inline"
                          onsubmit="return confirm('Delete “{{ $p->name }}”? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button class="btn sm danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="8">No products match.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div>{{ $products->links() }}</div>
@endsection
