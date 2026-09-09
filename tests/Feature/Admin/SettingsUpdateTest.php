<?php

namespace Tests\Feature\Admin;

use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsUpdateTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_saving_writes_mail_config_to_env_and_content_to_the_database(): void
    {
        $tmp = tempnam(sys_get_temp_dir(), 'env');
        file_put_contents($tmp, "APP_NAME=Bedeck\nMAIL_MAILER=log\nMAIL_FROM_ADDRESS=\"old@example.com\"\n");

        $this->app->useEnvironmentPath(dirname($tmp));
        $this->app->loadEnvironmentFrom(basename($tmp));
        $this->assertSame($tmp, $this->app->environmentFilePath());

        $admin = User::where('is_admin', true)->firstOrFail();

        $this->actingAs($admin)->put(route('admin.settings.update'), [
            'company_name' => 'Bedeck International',
            'mail_enabled' => '1',
            'mail_host' => 'smtp.gmail.com',
            'mail_port' => '587',
            'mail_security' => 'tls',
            'mail_username' => 'bot@gmail.com',
            'mail_password' => 'sixteencharsx123',
            'mail_from_name' => 'Bedeck International',
            'mail_from_address' => 'bot@gmail.com',
            'enquiry_to' => 'sales@bedeck.lk',
        ])->assertRedirect()->assertSessionHas('success');

        $env = file_get_contents($tmp);

        $this->assertStringContainsString('MAIL_MAILER=smtp', $env);
        $this->assertStringContainsString('MAIL_HOST=smtp.gmail.com', $env);
        $this->assertStringContainsString('MAIL_SCHEME=smtp', $env);
        $this->assertStringContainsString('MAIL_USERNAME=bot@gmail.com', $env);
        $this->assertStringContainsString('MAIL_PASSWORD=sixteencharsx123', $env);
        $this->assertStringContainsString('MAIL_ENQUIRY_TO=sales@bedeck.lk', $env);
        $this->assertStringNotContainsString('old@example.com', $env);

        $this->assertSame('Bedeck International', SiteSetting::get('company_name'));

        @unlink($tmp);
    }

    public function test_blank_password_keeps_the_existing_one(): void
    {
        $tmp = tempnam(sys_get_temp_dir(), 'env');
        file_put_contents($tmp, "MAIL_MAILER=smtp\nMAIL_PASSWORD=keepme123\n");

        $this->app->useEnvironmentPath(dirname($tmp));
        $this->app->loadEnvironmentFrom(basename($tmp));

        $admin = User::where('is_admin', true)->firstOrFail();

        $this->actingAs($admin)->put(route('admin.settings.update'), [
            'mail_enabled' => '1',
            'mail_username' => 'bot@gmail.com',
            'mail_password' => '', // untouched
            'enquiry_to' => 'sales@bedeck.lk',
        ])->assertRedirect();

        $this->assertStringContainsString('MAIL_PASSWORD=keepme123', file_get_contents($tmp));

        @unlink($tmp);
    }

    public function test_settings_screen_is_admin_only(): void
    {
        $this->get(route('admin.settings.edit'))->assertRedirect(route('admin.login'));
    }
}
