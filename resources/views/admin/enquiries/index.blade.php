@extends('layouts.admin')
@section('title', 'Enquiries')

@section('content')
<div class="admin-head"><h1>Enquiries</h1></div>

<div class="table-wrap">
    <table>
        <thead><tr><th>Received</th><th>Name</th><th>Email</th><th>Product</th><th>Subject</th><th></th></tr></thead>
        <tbody>
        @forelse($enquiries as $e)
            <tr style="{{ $e->is_read ? '' : 'font-weight:800;background:#fbfdfc' }}">
                <td>{{ $e->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $e->name }}</td>
                <td>{{ $e->email }}</td>
                <td>{{ optional($e->product)->name ?? '—' }}</td>
                <td>{{ \Illuminate\Support\Str::limit($e->subject ?: $e->message, 44) }}</td>
                <td style="white-space:nowrap">
                    <a class="btn sm" href="{{ route('admin.enquiries.show', $e) }}">Open</a>
                    <form action="{{ route('admin.enquiries.destroy', $e) }}" method="post" style="display:inline" onsubmit="return confirm('Delete this enquiry?')">
                        @csrf @method('DELETE')
                        <button class="btn sm danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">No enquiries yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div>{{ $enquiries->links() }}</div>
@endsection
