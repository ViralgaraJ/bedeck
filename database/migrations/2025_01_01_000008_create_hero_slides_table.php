<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('page_key', 30)->index();
            $table->string('image');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Carry over the carousel images each page already ships with, so
        // switching to the admin-managed carousel doesn't change what visitors
        // see until an admin actually edits it.
        $defaults = [
            'home' => collect(range(1, 7))->map(fn ($n) => sprintf('assets/images/site/hero-slide-%02d.webp', $n)),
            'about' => collect([
                'assets/images/pages/automation-software-technology.webp',
                'assets/images/pages/petroleum-fuel-station-automation.jpg',
                'assets/images/pages/engineering-consulting-services.jpg',
                'assets/images/pages/industrial-engineering-gears.jpg',
            ]),
            'services' => collect([
                'assets/images/pages/process-instrumentation-pressure-gauge.jpg',
                'assets/images/pages/mep-mechanical-pump-installation.jpg',
                'assets/images/pages/process-control-automation-plant.jpg',
                'assets/images/pages/industrial-valve-closeup.jpg',
            ]),
            'products' => collect([
                'assets/images/pages/fluidwell-flow-meters-product-range.png',
                'assets/images/pages/energy-management-system-thermostat.jpg',
                'assets/images/pages/building-mechanical-piping-system.jpg',
                'assets/images/pages/pressure-gauge-instrumentation.jpg',
            ]),
            'partners' => collect([
                'assets/images/pages/business-partnership-consulting.jpg',
                'assets/images/pages/building-management-system-network.png',
                'assets/images/pages/product-engineering-data-analytics.jpg',
                'assets/images/pages/industrial-iot-engineering-network.jpg',
            ]),
            'contact' => collect([
                'assets/images/pages/contact-icons-phone-email.jpg',
                'assets/images/pages/contact-us-icon-blocks.jpeg',
                'assets/images/pages/business-contact-support-icons.webp',
            ]),
        ];

        $now = now();
        $rows = [];

        foreach ($defaults as $pageKey => $images) {
            $images->filter(fn ($path) => is_file(public_path($path)))
                ->values()
                ->each(function (string $path, int $index) use ($pageKey, $now, &$rows) {
                    $rows[] = [
                        'page_key' => $pageKey,
                        'image' => $path,
                        'sort_order' => $index,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                });
        }

        if ($rows) {
            DB::table('hero_slides')->insert($rows);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
