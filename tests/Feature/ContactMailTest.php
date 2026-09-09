<?php

namespace Tests\Feature;

use App\Mail\EnquiryNotification;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactMailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_submitting_the_contact_form_emails_the_configured_recipient(): void
    {
        Mail::fake();
        config(['mail.enquiry_to' => 'enquiries@bedeck.test']);

        $product = Product::first();

        $this->post('/contact', [
            'name' => 'Priya Fernando',
            'email' => 'priya@example.com',
            'company' => 'Acme Refinery',
            'product_id' => $product->id,
            'message' => 'Please quote 3 units.',
        ])->assertRedirect();

        $this->assertDatabaseHas('enquiries', ['email' => 'priya@example.com']);

        Mail::assertSent(EnquiryNotification::class, function (EnquiryNotification $mail) {
            return $mail->hasTo('enquiries@bedeck.test')
                && $mail->hasReplyTo('priya@example.com')
                && $mail->enquiry->name === 'Priya Fernando';
        });
    }

    public function test_form_still_succeeds_when_no_recipient_is_configured(): void
    {
        Mail::fake();
        config(['mail.enquiry_to' => null, 'mail.from.address' => null]);

        $this->post('/contact', [
            'name' => 'No Mail',
            'email' => 'nomail@example.com',
            'message' => 'Hello.',
        ])->assertRedirect()->assertSessionHas('success');

        Mail::assertNothingSent();
        $this->assertDatabaseHas('enquiries', ['email' => 'nomail@example.com']);
    }

    public function test_admin_can_send_a_test_email(): void
    {
        Mail::fake();
        $admin = User::where('is_admin', true)->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.settings.test-mail'), ['test_to' => 'me@bedeck.test'])
            ->assertRedirect()
            ->assertSessionHas('success')
            ->assertSessionMissing('error');
    }

    public function test_test_email_requires_a_valid_address(): void
    {
        $admin = User::where('is_admin', true)->firstOrFail();

        $this->actingAs($admin)
            ->post(route('admin.settings.test-mail'), ['test_to' => 'not-an-email'])
            ->assertSessionHasErrors('test_to');
    }

    public function test_settings_screen_shows_the_mail_fields(): void
    {
        $admin = User::where('is_admin', true)->firstOrFail();

        $this->actingAs($admin)->get(route('admin.settings.edit'))
            ->assertOk()
            ->assertSee('Email delivery (Gmail SMTP)')
            ->assertSee('Gmail App Password')
            ->assertSee('Send enquiry notifications to');
    }
}
