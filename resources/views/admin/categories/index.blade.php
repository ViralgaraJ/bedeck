@extends('layouts.admin')
@section('title', 'Categories')

@section('content')
<div class="admin-head"><h1>Categories</h1>
    <a class="btn accent" href="{{ route('admin.categories.create') }}">+ Add category</a>
</div>

<div class="table-wrap">
    <table>
        <thead><tr><th>Name</th><th>Slug</th><th>Products</th><th>Order</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @forelse($categories as $c)
            <tr>
                <td><strong>{{ $c->name }}</strong></td>
                <td>{{ $c->slug }}</td>
                <td>{{ $c->products_count }}</td>
                <td>{{ $c->sort_order }}</td>
                <td>{{ $c->is_active ? 'Active' : 'Hidden' }}</td>
                <td style="white-space:nowrap">
                    <a class="btn sm" href="{{ route('admin.categories.edit', $c) }}">Edit</a>
                    <form action="{{ route('admin.categories.destroy', $c) }}" method="post" style="display:inline"
                          onsubmit="return confirm('Delete this category?')">
                        @csrf @method('DELETE')
                        <button class="btn sm danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">No categories.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
