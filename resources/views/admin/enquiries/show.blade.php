@extends('layouts.admin')
@section('title', 'Enquiry from '.$enquiry->name)

@section('content')
<div class="admin-head"><h1>Enquiry</h1><a class="btn" href="{{ route('admin.enquiries.index') }}">← Back</a></div>

<div class="card">
    <table>
        <tr><th style="width:170px">Received</th><td>{{ $enquiry->created_at->format('Y-m-d H:i') }} ({{ $enquiry->created_at->diffForHumans() }})</td></tr>
        <tr><th>Name</th><td>{{ $enquiry->name }}</td></tr>
        <tr><th>Email</th><td><a href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a></td></tr>
        <tr><th>Company</th><td>{{ $enquiry->company ?: '—' }}</td></tr>
        <tr><th>Phone</th><td>{{ $enquiry->phone ?: '—' }}</td></tr>
        <tr><th>Product</th><td>{{ optional($enquiry->product)->name ?? '—' }}</td></tr>
        <tr><th>Subject</th><td>{{ $enquiry->subject ?: '—' }}</td></tr>
        <tr><th>IP</th><td>{{ $enquiry->ip_address ?: '—' }}</td></tr>
    </table>
    <h3>Message</h3>
    <p style="white-space:pre-wrap">{{ $enquiry->message }}</p>
    <a class="btn primary" href="mailto:{{ $enquiry->email }}?subject={{ rawurlencode('Re: '.($enquiry->subject ?: 'Your enquiry to Bedeck International')) }}">Reply by email</a>
</div>
@endsection
