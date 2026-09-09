@php($e = $enquiry)
<!doctype html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:24px;background:#f4f7f7;font-family:Arial,Helvetica,sans-serif;color:#1f2933;">
  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;">
    <tr><td style="padding:0 0 16px;font-size:13px;color:#6b7280;">
      {{ config('app.name') }} &mdash; website contact form
    </td></tr>
    <tr><td style="background:#ffffff;border:1px solid #e3e8e8;border-radius:12px;padding:24px;">
      <h1 style="margin:0 0 4px;font-size:20px;color:#0e6f70;">New enquiry received</h1>
      <p style="margin:0 0 20px;font-size:13px;color:#6b7280;">
        {{ $e->created_at?->format('D, d M Y H:i') }} &middot; IP {{ $e->ip_address ?: 'n/a' }}
      </p>

      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;">
        @php($row = fn ($k, $v) => $v ? '<tr><td style="padding:6px 12px 6px 0;color:#6b7280;white-space:nowrap;vertical-align:top;">'.$k.'</td><td style="padding:6px 0;">'.$v.'</td></tr>' : '')
        {!! $row('Name', e($e->name)) !!}
        {!! $row('Email', '<a href="mailto:'.e($e->email).'" style="color:#0e6f70;">'.e($e->email).'</a>') !!}
        {!! $row('Company', e($e->company)) !!}
        {!! $row('Phone', e($e->phone)) !!}
        {!! $row('Subject', e($e->subject)) !!}
        {!! $row('Product', e(optional($e->product)->name)) !!}
      </table>

      <p style="margin:18px 0 6px;color:#6b7280;font-size:13px;">Message</p>
      <div style="white-space:pre-wrap;font-size:14px;line-height:1.55;padding:14px 16px;background:#f3f7f6;border-radius:8px;">{{ $e->message }}</div>

      <p style="margin:22px 0 0;">
        <a href="{{ url('/admin/enquiries/'.$e->id) }}" style="display:inline-block;background:#0e6f70;color:#fff;text-decoration:none;padding:10px 18px;border-radius:8px;font-size:14px;">Open in admin</a>
        &nbsp;
        <a href="mailto:{{ $e->email }}" style="display:inline-block;background:#eef2f2;color:#1f2933;text-decoration:none;padding:10px 18px;border-radius:8px;font-size:14px;">Reply to sender</a>
      </p>
    </td></tr>
    <tr><td style="padding:16px 0 0;font-size:12px;color:#9aa5a5;">
      Reply to this email to respond directly to {{ $e->name }}.
    </td></tr>
  </table>
</body>
</html>
