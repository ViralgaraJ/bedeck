<footer class="site-footer">
    <div class="footer-grid">
        <div class="footer-brand">
            <a class="footer-logo" href="{{ route('home') }}">
                <img src="{{ asset('assets/optimized/bedeck-logo2.jpg') }}" alt="{{ setting('company_name', 'Bedeck International') }} logo">
            </a>
            <p>Industrial engineering, product supply and consultation in Sri Lanka.</p>
            <div class="footer-social">
                <a href="{{ whatsapp_link() }}" target="_blank" rel="noopener" aria-label="Chat on WhatsApp">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2c-5.5 0-9.96 4.46-9.96 9.96 0 1.76.46 3.48 1.34 5L2 22l5.2-1.36a9.9 9.9 0 0 0 4.84 1.24h.01c5.5 0 9.96-4.46 9.96-9.96 0-2.66-1.04-5.16-2.92-7.04A9.9 9.9 0 0 0 12.04 2zm0 1.67c2.2 0 4.27.86 5.83 2.42a8.2 8.2 0 0 1 2.42 5.85c0 4.55-3.7 8.25-8.26 8.25a8.2 8.2 0 0 1-4.2-1.15l-.3-.18-3.12.82.83-3.04-.2-.31a8.24 8.24 0 0 1-1.26-4.39c0-4.55 3.7-8.24 8.25-8.24zm-2.9 4.36c-.15 0-.4.06-.6.29-.2.22-.79.77-.79 1.87s.81 2.17.92 2.32c.11.15 1.57 2.4 3.85 3.36.54.23.96.37 1.29.48.54.17 1.03.15 1.42.09.43-.06 1.34-.55 1.53-1.08.19-.53.19-.98.13-1.08-.06-.09-.2-.15-.43-.26-.22-.11-1.34-.66-1.55-.74-.21-.08-.36-.11-.51.11-.15.22-.58.74-.71.89-.13.15-.26.17-.48.06-.22-.11-.94-.35-1.79-1.11-.66-.59-1.11-1.32-1.24-1.54-.13-.22-.01-.34.1-.45.1-.1.22-.26.33-.39.11-.13.15-.22.22-.37.07-.15.04-.28-.02-.39-.06-.11-.5-1.24-.7-1.7-.18-.44-.37-.38-.51-.39-.13-.01-.28-.01-.43-.01z"/></svg>
                </a>
                <a href="https://www.linkedin.com/in/bedeck-international-aab605220/" target="_blank" rel="noopener noreferrer" aria-label="Bedeck International on LinkedIn">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.28 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.75M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
                </a>
                <a href="mailto:{{ setting('email', 'info@bedeckinternational.lk') }}" aria-label="Email Bedeck International">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                </a>
            </div>
        </div>

        <nav class="footer-col" aria-label="Site">
            <h3>Explore</h3>
            <a href="{{ route('about') }}">About</a>
            <a href="{{ route('services') }}">Services</a>
            <a href="{{ route('products.index') }}">Products</a>
            <a href="{{ route('partners') }}">Partners</a>
            <a href="{{ route('contact') }}">Contact</a>
        </nav>

        <div class="footer-col footer-col--contact">
            <h3>Get in touch</h3>
            <a href="{{ whatsapp_link() }}" target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                <span>{{ setting('whatsapp_display', '+94 77 171 1440') }}</span>
            </a>
            <a href="mailto:{{ setting('email', 'info@bedeckinternational.lk') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <span>{{ setting('email', 'info@bedeckinternational.lk') }}</span>
            </a>
            @php($address = setting('address', '10/3, Salmal Place, Devala Road, Depanama, Pannipitiya 10230, Sri Lanka'))
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($address) }}" target="_blank" rel="noopener" class="footer-address">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <span>{{ $address }}</span>
            </a>
        </div>
    </div>

    <div class="footer-bottom">
        <span>&copy; {{ date('Y') }} {{ setting('company_name', 'Bedeck International') }}. All rights reserved. &nbsp;|&nbsp; Crafted by ViralgaraJ</span>
        <span>{{ setting('company_role', 'Engineering Consultants') }} in Sri Lanka since {{ setting('established_year', '2010') }}</span>
    </div>
</footer>
