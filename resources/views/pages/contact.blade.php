@extends('layouts.public')

@section('title', 'Contact '.setting('company_name', 'Bedeck International').' | Request a Quote Sri Lanka')
@section('meta_description', 'Contact Bedeck International in Pannipitiya, Sri Lanka for industrial products, metering, pumps, valves, gauges, fuel management, automation and engineering support.')

@section('content')
@include('partials.page-hero', [
    'eyebrow' => 'Contact',
    'heading' => 'Request product sourcing, quotation or engineering consultation.',
    'text' => 'Use WhatsApp, email or the enquiry form to contact Bedeck International about industrial equipment and engineering requirements.',
    'image' => 'assets/images/site/page-contact-01.webp',
])

<section class="section">
    <div class="contact-band" data-reveal>
        <div>
            <p class="eyebrow" style="color:#f0ba67">Contact / Request a Quote</p>
            <h2>Talk to {{ setting('company_name', 'Bedeck International') }} about product sourcing or engineering support.</h2>
            <address>
                <span>{{ setting('address', '10/3, Salmal Place, Devala Road, Depanama, Pannipitiya 10230, Sri Lanka') }}</span>
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', setting('phone', '+94112746006')) }}">{{ setting('phone', '+94 11 274 6006') }}</a>
                <a href="{{ whatsapp_link() }}" target="_blank" rel="noopener">{{ setting('whatsapp_display', '+94 77 171 1440') }} on WhatsApp</a>
                <a href="mailto:{{ setting('email', 'info@bedeckinternational.lk') }}">{{ setting('email', 'info@bedeckinternational.lk') }}</a>
                @if(setting('email_alt'))<a href="mailto:{{ setting('email_alt') }}">{{ setting('email_alt') }}</a>@endif
            </address>
        </div>

        <form class="contact-form" method="post" action="{{ route('contact.store') }}">
            @csrf
            @if(session('success'))<div class="alert success">{{ session('success') }}</div>@endif
            @if(session('error'))<div class="alert error">{{ session('error') }}</div>@endif
            @if($errors->any())
                <div class="alert error">
                    <ul style="margin:0;padding-left:1.1rem">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="form-row">
                <label>Name * <input name="name" value="{{ old('name') }}" autocomplete="name" placeholder="Your full name" required></label>
                <label>Email * <input type="email" name="email" value="{{ old('email') }}" autocomplete="email" placeholder="name@company.com" required></label>
            </div>
            <div class="form-row">
                <label>Company <input name="company" value="{{ old('company') }}" autocomplete="organization" placeholder="Company / Organization"></label>
                <label>Phone <input name="phone" value="{{ old('phone') }}" autocomplete="tel" placeholder="+94 77 000 0000"></label>
            </div>
            <label>Product of interest
                <select name="product_id">
                    <option value="">— General enquiry —</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" @selected(old('product_id') == $p->id)>{{ $p->name }}</option>
                    @endforeach
                </select>
            </label>
            <label>Subject <input name="subject" value="{{ old('subject') }}"></label>
            <label>Message <textarea name="message" rows="5" required>{{ old('message') }}</textarea></label>
            <div class="hp"><label>Leave this empty <input name="website" tabindex="-1" autocomplete="off"></label></div>
            <button class="button primary block" type="submit">Send Enquiry</button>
        </form>
    </div>
</section>
@endsection
