@extends('layouts.admin')
@section('title', 'Partners')

@section('content')
<div class="admin-head">
    <h1>Partners</h1>
    <a class="btn accent" href="{{ route('admin.partners.create') }}">+ Add partner</a>
</div>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>Logo</th>
                <th>Name</th>
                <th>Country</th>
                <th>Website</th>
                <th>Order</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse($partners as $p)
            <tr>
                <td>
                    @if($p->logo)
                        <img class="thumb" src="{{ asset($p->logo) }}" alt="{{ $p->name }} logo">
                    @else
                        <span style="color:var(--faint)">No logo</span>
                    @endif
                </td>
                <td><strong>{{ $p->name }}</strong></td>
                <td>{{ $p->country ?: '—' }}</td>
                <td>
                    @if($p->website)
                        <a href="{{ $p->website }}" target="_blank" rel="noopener">{{ parse_url($p->website, PHP_URL_HOST) ?: $p->website }} ↗</a>
                    @else
                        <span style="color:var(--faint)">—</span>
                    @endif
                </td>
                <td>{{ $p->sort_order }}</td>
                <td>
                    <span class="badge" style="{{ $p->is_active ? 'color:#9cf3e4' : 'color:var(--faint)' }}">
                        {{ $p->is_active ? 'Active' : 'Hidden' }}
                    </span>
                </td>
                <td style="white-space:nowrap">
                    <a class="btn sm" href="{{ route('admin.partners.edit', $p) }}">Edit</a>
                    <form action="{{ route('admin.partners.destroy', $p) }}" method="post" style="display:inline"
                          onsubmit="return confirm('Delete partner “{{ addslashes($p->name) }}”?')">
                        @csrf @method('DELETE')
                        <button class="btn sm danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:2rem">No partners found. Click <strong>+ Add partner</strong> to add one.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
