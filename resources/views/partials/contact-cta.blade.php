<section class="section">
    <div class="contact-cta tilt" data-reveal data-tilt>
        <div>
            <p class="eyebrow">Need product sourcing or engineering support?</p>
            <h2>Send your enquiry once, and {{ setting('company_name', 'Bedeck International') }} will respond through the right channel.</h2>
            <p>Use the contact page for quotation requests, product enquiries, attachments and project information.</p>
        </div>
        <div class="cta-actions">
            <a class="button primary" href="{{ route('contact') }}">Go to Contact Page</a>
            <a class="button ghost" href="{{ whatsapp_link() }}" target="_blank" rel="noopener">WhatsApp Enquiry</a>
        </div>
    </div>
</section>
