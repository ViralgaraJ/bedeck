<footer class="site-footer">
    <div>
        <strong>{{ setting('company_name', 'Bedeck International') }}</strong>
        <span>Industrial engineering, product supply and consultation in Sri Lanka.</span>
    </div>
    <div class="footer-links">
        <a href="{{ route('about') }}">About</a>
        <a href="{{ route('services') }}">Services</a>
        <a href="{{ route('products.index') }}">Products</a>
        <a href="{{ route('partners') }}">Partners</a>
        <a href="{{ route('contact') }}">Contact</a>
        <a href="{{ whatsapp_link() }}" target="_blank" rel="noopener">WhatsApp {{ setting('whatsapp_display', '+94 77 171 1440') }}</a>
        <a href="mailto:{{ setting('email', 'info@bedeckinternational.lk') }}">{{ setting('email', 'info@bedeckinternational.lk') }}</a>
    </div>
</footer>
<div class="footer-bottom">
    &copy; {{ date('Y') }} {{ setting('company_name', 'Bedeck International') }}. All rights reserved. &nbsp;·&nbsp; {{ setting('address', '10/3, Salmal Place, Devala Road, Depanama, Pannipitiya 10230, Sri Lanka') }}
</div>
