<?php

namespace Tests\Unit;

use App\Support\EnvWriter;
use PHPUnit\Framework\TestCase;

class EnvWriterTest extends TestCase
{
    private string $file;

    protected function setUp(): void
    {
        $this->file = tempnam(sys_get_temp_dir(), 'env');
        file_put_contents($this->file, "APP_NAME=Bedeck\nMAIL_MAILER=log\nMAIL_HOST=old.host\n");
    }

    protected function tearDown(): void
    {
        @unlink($this->file);
    }

    public function test_it_updates_existing_keys_and_appends_new_ones(): void
    {
        EnvWriter::write([
            'MAIL_HOST' => 'smtp.gmail.com',
            'MAIL_USERNAME' => 'test@gmail.com',
            'MAIL_ENQUIRY_TO' => 'sales@bedeck.lk',
        ], $this->file);

        $out = file_get_contents($this->file);

        $this->assertStringContainsString("MAIL_HOST=smtp.gmail.com\n", $out);
        $this->assertStringNotContainsString('old.host', $out);
        $this->assertStringContainsString("MAIL_USERNAME=test@gmail.com\n", $out);
        $this->assertStringContainsString("MAIL_ENQUIRY_TO=sales@bedeck.lk\n", $out);
        $this->assertStringContainsString("APP_NAME=Bedeck\n", $out);
        $this->assertSame(1, substr_count($out, 'MAIL_HOST='));
    }

    public function test_it_quotes_values_with_spaces_or_specials(): void
    {
        EnvWriter::write([
            'MAIL_FROM_NAME' => 'Bedeck International',
            'MAIL_PASSWORD' => 'abcd efgh $ijk "l"',
        ], $this->file);

        $out = file_get_contents($this->file);

        $this->assertStringContainsString('MAIL_FROM_NAME="Bedeck International"', $out);
        $this->assertStringContainsString('MAIL_PASSWORD="abcd efgh $ijk \\"l\\""', $out);
    }

    public function test_it_ignores_unsafe_key_names(): void
    {
        EnvWriter::write(['MAIL HOST; rm -rf' => 'x'], $this->file);
        $out = file_get_contents($this->file);

        $this->assertStringContainsString("MAILHOSTRMRF=x\n", $out);
        $this->assertStringNotContainsString('rm -rf', $out);
    }
}
