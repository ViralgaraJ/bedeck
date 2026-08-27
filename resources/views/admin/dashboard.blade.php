@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="admin-head"><h1>Dashboard</h1>
    <a class="btn accent" href="{{ route('admin.products.create') }}">+ Add product</a>
</div>

<div class="stat-grid">
    <div class="stat"><b>{{ $productCount }}</b><span>Products ({{ $activeCount }} active)</span></div>
    <div class="stat"><b>{{ $featuredCount }}</b><span>Featured products</span></div>
    <div class="stat"><b>{{ $categoryCount }}</b><span>Categories</span></div>
    <div class="stat"><b>{{ $partnerCount }}</b><span>Partners</span></div>
    <div class="stat"><b>{{ $unreadEnquiries }}</b><span>Unread enquiries</span></div>
</div>

<div class="card">
    <h2 style="margin-top:0">Recently added products</h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th></th><th>Name</th><th>Category</th><th>Brand</th><th>Status</th><th></th></tr></thead>
            <tbody>
            @forelse($recentProducts as $p)
                <tr>
                    <td><img class="thumb" src="{{ $p->thumb_url }}" alt=""></td>
                    <td>{{ $p->name }}</td>
                    <td>{{ optional($p->category)->name ?? '—' }}</td>
                    <td>{{ $p->brand ?? '—' }}</td>
                    <td>{{ $p->is_active ? 'Active' : 'Hidden' }}</td>
                    <td><a class="btn sm" href="{{ route('admin.products.edit', $p) }}">Edit</a></td>
                </tr>
            @empty
                <tr><td colspan="6">No products yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <h2 style="margin-top:0">Latest enquiries</h2>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Received</th><th>Name</th><th>Email</th><th>Subject</th><th></th></tr></thead>
            <tbody>
            @forelse($recentEnquiries as $e)
                <tr style="{{ $e->is_read ? '' : 'font-weight:800' }}">
                    <td>{{ $e->created_at->diffForHumans() }}</td>
                    <td>{{ $e->name }}</td>
                    <td>{{ $e->email }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($e->subject ?: $e->message, 40) }}</td>
                    <td><a class="btn sm" href="{{ route('admin.enquiries.show', $e) }}">Open</a></td>
                </tr>
            @empty
                <tr><td colspan="5">No enquiries yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
